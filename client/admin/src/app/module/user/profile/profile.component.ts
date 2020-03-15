import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {FormData} from '../../../share/form-data';

@Component({
    selector: 'app-profile',
    templateUrl: './profile.component.html',
    styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {

    constructor(
        public app: AppService,
        private router: Router
    ) {
    }

    public fd;
    private data = {
        email: '',
        name: '',
        username: '',
        employee_id: '',
        phone_number: 0,
        group: 1,
        password: '',
        password_confirmation: ''
    };
    public profile;

    ngOnInit() {
        this.fd = new FormData(this.data);

        this.app.get('users/profile').subscribe((res: any) => {
            this.fd.setData(res.data);
            this.profile = res.data;
        });
    }

    save() {
        this.app.post('users/profile', this.fd.form.value).subscribe((data: any) => {
            this.app.flashSuccess('Profile has been updated', false);
            // if (data.profile.group == this.app.constant.GROUP_ORGANIZATION) {
            //     this.router.navigate(['/member/list']);
            // } else if (data.profile.group == this.app.constant.GROUP_CONTENT_CREATOR) {
            //     this.router.navigate(['/media']);
            // } else {
            this.app.curUser = data.profile;
            this.router.navigate(['/dashboard']);
            // }
        });
    }

}
