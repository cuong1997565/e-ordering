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
    public FeatureItem = {
        id: '',
        name: '',
    };
    public feature_id;

    ngOnInit() {
        this.route.params.subscribe((e) => {
            const dataQuery = {limit: 10, sort: 'name', direction: 'asc'};

            if (e.id) {
                this.app.setConfig('feature_id', e.id);
                dataQuery['feature_id'] = e.id;
                this.feature_id = e.id;
            }

            this.app.get('features', {'id': e.id}).subscribe((res: any) => {
                this.FeatureItem = res.data[0];
            });

            this.ld = new ListData(this.app, this.route, 'feature-items', dataQuery);

        });
    }

}
