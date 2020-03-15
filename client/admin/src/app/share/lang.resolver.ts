import { Injectable } from '@angular/core';
import { Resolve } from '@angular/router';
import { AppService } from "./app.service";
import { forkJoin } from 'rxjs';

@Injectable()
export class LangResolver implements Resolve<any>
{
    constructor(private app: AppService) {}

    resolve()
    {
        return this.app.get('langs/get_langs');
    }
}
