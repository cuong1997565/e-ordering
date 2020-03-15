import { Component, OnInit } from '@angular/core';
import { AppService } from "../../app.service";

@Component({
  selector: 'ele-user-info',
  templateUrl: './info.component.html'
})
export class UserInfoComponent implements OnInit {

  constructor(public app: AppService) { }

  ngOnInit() {
  }

}
