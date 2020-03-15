import { Component, OnInit } from '@angular/core';
import { FormData } from "../../../share/form-data";
import { AppService } from "../../../share/app.service";

@Component({
    selector: 'app-password',
    templateUrl: './password.component.html',
    styleUrls: ['./password.component.css']
})
export class PasswordComponent implements OnInit {

    constructor(private app: AppService) { }

    public fd;
    private data = {
        old_password: '',
        new_password: '',
        confirm_password: ''
    };

    ngOnInit() {
        this.fd = new FormData(this.data);
    }

    save() {
        this.app.post('users/password', this.fd.form.value).subscribe((data:any) =>
        {
            this.app.flashSuccess('Password has been changed',true);
        });
    }

}
