import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Params, Router} from '@angular/router';
import { DEFAULT_LANG, langList } from '../../../../share/translation/config';
import { AppService } from '../share/app.service';
import * as _ from 'lodash';

@Component({
  selector: 'app-lang-route',
  templateUrl: './lang-route.component.html'
})

export class LangRouteComponent implements OnInit {

  constructor(
      private app: AppService,
      private route: ActivatedRoute,
      private router: Router) { }

  ngOnInit() {

      // Redirect language
      this.route.params.subscribe((params: Params) => {

          // Get default language
          let defaultLang = this.app.getConfig('LANG_TOP', DEFAULT_LANG);
          if ( defaultLang === 'undefined') { defaultLang = DEFAULT_LANG };

          if (!params['lang']) {
              this.router.navigate(['/' + defaultLang]);
          }
          else
          {
              if (_.includes(_.map(langList, 'language'), params['lang']) === false) {
                  let url = window.location.pathname.replace(params['lang'], defaultLang);
                  this.router.navigate([url]);
                  return;
              }
          }

          this.app.setConfig('LANG_TOP', params['lang']);
          this.app.curLang = params['lang'];
      });

      // App resolver
      this.route.data.subscribe((res:any) => {
          if(typeof res.app !== 'undefined')
          {
              this.app.appResolve(res.app);
          }
      });
  }
}
