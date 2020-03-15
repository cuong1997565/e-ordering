import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../share/form-data';
import * as $ from 'jquery';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {
  public fd;
  public url;
  private data = {
      id: '',
      name: '',
      code: '',
      email: '',
      phone: '',
      address: '',
      active: 0
  };

  constructor(public app: AppService,
              private route: ActivatedRoute,
              private router: Router
  ) { }

  ngOnInit() {
      this.fd = new FormData(this.data);
      if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_CREATE_FACTORY]) {
          this.router.navigate(['dashboard']);
      }
      if (!this.app.curUser.role.permissions[this.app.constant.PERMISSION_EDIT_FACTORY]) {
          this.router.navigate(['dashboard']);
      }
      if (this.route.snapshot.params['id']) {
          this.fd.isNew = false;
          this.app.get('factories', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
              this.fd.setData(res.data[0]);
          });
      }
  }

   save() {
       this.url = 'factories/' + this.route.snapshot.params['id'];
       this.fd.form.value.active = this.fd.form.value.active ? 1 : 0;
       if (this.route.snapshot.params['id']) {
           this.fd.form.value.email = $.trim(this.fd.form.value.email);

           this.app.post(this.url, this.fd.form.value).subscribe((data: any) => {
               this.app.flashSuccess('Factory has been saved');
               return this.router.navigate(['/factory']);
           });
       } else {
           this.fd.form.value.email = $.trim(this.fd.form.value.email);
           this.app.post('factories', this.fd.form.value).subscribe((data: any) => {
               this.app.flashSuccess('Factory has been saved');
               return this.router.navigate(['/factory']);
           });
       }
   }

}
