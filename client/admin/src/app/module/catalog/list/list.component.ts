import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute} from '@angular/router';
import {ListData} from '../../../share/list-data';
import {UploadService} from '../../../share/element/upload/upload.service';
import {FormData} from '../../../share/form-data';

import * as $ from 'jquery';

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    public ld;
    public fd;
    public url;
    public disabled: boolean = false;
    private data = {
        id: '',
        file: '',
        name: ''
    };

    constructor(public app: AppService, private route: ActivatedRoute, private upload: UploadService) {
    }

    ngOnInit() {
        this.fd = new FormData(this.data);
        this.getList();
    }

    getList() {
        this.ld = new ListData(this.app, this.route,
            'catalogs',
            {
                limit: this.app.constant.LIMIT_USER_MANAGEMENT,
                sort: 'created_at',
                direction: this.app.constant.ORDER_ASC
            });
    }

    save() {
        this.fd.form.value['file'] = (this.upload.getDataFile('file') ? this.upload.getDataFile('file') : '');
        this.fd.form.value['name'] = this.upload.getDataFile('file').name;
        this.disabled = true;
        this.app.post('catalogs/save-file', this.fd.form.value).subscribe((res: any) => {
            this.app.flashSuccess('Data has been saved', true);
            this.ld = new ListData(this.app, this.route,
                'catalogs',
                {
                    limit: this.app.constant.LIMIT_USER_MANAGEMENT,
                    sort: 'created_at',
                    direction: this.app.constant.ORDER_ASC
                });
            this.upload.setDataFile('file', '');
            $(':input').val('');
            this.disabled = false;
        }, (error) => {
            this.disabled = false;
        });
    }

    del(id, item) {
        this.url = 'catalogs/' + id + '/delete';
        this.ld.delete(this.url, item);
    }

}
