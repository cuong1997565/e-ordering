import {Component, OnInit} from '@angular/core';
import {AppService} from '../../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormData} from '../../../../share/form-data';
import {UploadService} from '../../../../share/element/upload/upload.service';

@Component({
    selector: 'app-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

    constructor
    (
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router,
        private upload: UploadService
    ) {
    }

    public listSampleType;

    public fd;
    private data = {
        id: '',
        sample_type_id: null,
        name: '',
        image: '',
        avatar: '',
        gallery: '',
        description: '',
        content: '',
        demo_required: '{"vn":"demo vn","us":"demo us"}',
        demo: '',
        active: 1
    };

    private dataItem = {
        id: '',
        name: '',
        number: '',
        active: '',
    };

    ngOnInit() {
        this.fd = new FormData(this.data);
        this.fd.initChild('sample_items', this.dataItem);

        this.getListSampleType();

        if (this.route.snapshot.params['id']) {
            this.fd.isNew = false;
            this.app.get('samples/form', {'id': this.route.snapshot.params['id']}).subscribe((res: any) => {
                this.fd.setData(res.data);
            });
        }
    }

    getListSampleType() {
        this.app.get('sample_types').subscribe((res: any) => {
            this.listSampleType = this.app.arrToList(res.data, 'id', 'name');
        });
    }

    save() {
        this.fd.form.value['image'] = this.upload.getDataFile('image');
        this.app.post('samples/form', this.fd.form.value).subscribe((res: any) => {
            this.app.flashSuccess('Data has been saved');
            this.router.navigate(['/sample']);
        });
    }
}
