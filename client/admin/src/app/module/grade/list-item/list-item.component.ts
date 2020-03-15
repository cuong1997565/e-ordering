import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute} from '@angular/router';
import {ListData} from '../../../share/list-data';

@Component({
  selector: 'app-list-item',
  templateUrl: './list-item.component.html',
  styleUrls: ['./list-item.component.css']
})
export class ListItemComponent implements OnInit {

    constructor(public app: AppService, private route: ActivatedRoute) {
    }

    public ld;
    public url;
    public gradeGroup = {
        id: '',
        name: '',
    };
    public grade_group_id;
  ngOnInit() {
      this.route.params.subscribe((e) => {
          const dataQuery = {limit: 10, sort: 'name', direction: 'asc'};

          if (e.id) {
                this.app.setConfig('grade_group_id', e.id);
                dataQuery['grade_group_id'] = e.id;
                this.grade_group_id = e.id;
          }

          this.app.get('grade-groups', {'id': e.id}).subscribe((res: any) => {
              this.gradeGroup = res.data[0];
          });

          this.ld = new ListData(this.app, this.route, 'grades', dataQuery);

      });
  }

}
