import { Component, OnInit } from '@angular/core';
import {AppService} from '../../../share/app.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FormBuilder} from '@angular/forms';

@Component({
  selector: 'app-change-email',
  templateUrl: './change-email.component.html',
  styleUrls: ['./change-email.component.css']
})
export class ChangeEmailComponent implements OnInit {
    public email;
    public form;

  constructor(public app: AppService, private route: ActivatedRoute, private router: Router, private _fb: FormBuilder) { }

  ngOnInit() {
      this.form = this._fb.group(
          {
              email: ''
          });
  }

  changeEmail() {
      this.form.value.email =  this.route.snapshot.queryParams['email'];

      if (confirm('Are you sure you change the email')) {
          this.app.post('v1/users/change-email', this.form.value).subscribe((res) => {
               alert('Update email success');
              return this.router.navigate([`customer/login`]);
          }, (err) => {
               alert('Update email fail');
              return this.router.navigate([`customer/login`]);
          });
      }

  }

    login() {
        this.router.navigate(['/customer/login']);
    }

}
