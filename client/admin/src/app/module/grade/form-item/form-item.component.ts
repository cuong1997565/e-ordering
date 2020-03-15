import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';

@Component({
  selector: 'app-form-item',
  templateUrl: './form-item.component.html',
  styleUrls: ['./form-item.component.css']
})
export class FormItemComponent implements OnInit {

    constructor
    (
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router,
    ) {
    }

    public fd;
    public url;
    public data = {
        id: '',
        name: '',
        code: '',
        grade_group_id: null,
        display_name: '',
        active: 0
    };
    public gradeGroup = {
        id: '',
        name: ''
    }

    public grade_group_id;

  ngOnInit() {
      this.fd = new FormData(this.data);
      this.grade_group_id = JSON.parse(localStorage.getItem('grade_group_id'));
      if (this.grade_group_id) {
          this.app.get('grade-groups/', {'id': this.grade_group_id}).subscribe((res: any) => {
              this.gradeGroup = res.data[0];
          });
      }
      if (this.route.snapshot.params['id']) {
          this.fd.isNew = false;
          this.app.get('grades', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
              this.fd.setData(res.data[0]);
          });
      }
  }

  save() {
      this.fd.form.value.grade_group_id = this.grade_group_id;
      this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;

      this.url = 'grade/' + this.route.snapshot.params['id'];

      if (this.route.snapshot.params['id']) {
          this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
              this.app.flashSuccess('Grade has been saved');
              return this.router.navigate(['/grade/grade-item', this.grade_group_id]);
          });
      } else {
          this.app.post('grade', this.fd.form.value).subscribe((data: any) => {
              this.app.flashSuccess('Grade has been saved');
              return this.router.navigate(['/grade/grade-item', this.grade_group_id]);
          });
      }
  }

}
