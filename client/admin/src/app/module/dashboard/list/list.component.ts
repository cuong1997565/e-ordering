import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {AppService} from '../../../share/app.service';
import {ListData} from '../../../share/list-data';
import * as _ from 'lodash';
import {constant} from '../../../config/base';
declare var $: any;


@Component({
    selector: 'app-dashboard-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    public Po_Submit;
    public Po_Accept;
    public Po_Expired;
    public Po_Closed;
    public So_Draft;
    public So_Expired;
    public So_Confirmed;
    public So_Closed;
    public Dn_Draft;
    public Dn_WaitingForConfirm;
    public Dn_Approve;
    public Dn_Confirm;
    public crel_expired;
    public crel_upcoming_expired;

    public  List_Po_Submit: any = [];
    public List_Po_Processing: any = [];
    public List_Po_Expired: any = [];
    public List_So_Draf: any = [];
    public  List_So_Processing: any = [];
    public List_So_Close: any = [];
    public List_Dn_Draft: any = [];
    public List_Dn_WaitingForConfirm: any = [];
    public List_Dn_Approved: any = [];
    public List_Dn_Confirm: any = [];

    constructor(public app: AppService, private route: ActivatedRoute) {
    }

    ngOnInit() {
        let self = this;
        this.app.get('dashboards').subscribe((data: any) => {
            _.forEach(data.data, function (value) {
                if (value.type === constant.Po_Submit) {
                    self.Po_Submit = value;
                }
                if (value.type === constant.Po_Accept) {
                    self.Po_Accept = value;
                }
                if (value.type === constant.Po_Expired) {
                    self.Po_Expired = value;
                }
                if (value.type === constant.Po_Closed) {
                    self.Po_Closed = value;
                }
                if (value.type === constant.So_Draft) {
                    self.So_Draft = value;
                }
                if (value.type === constant.So_Expired) {
                    self.So_Expired = value;
                }
                if (value.type === constant.So_Confirmed) {
                    self.So_Confirmed = value;
                }
                if (value.type === constant.So_Closed) {
                    self.So_Closed = value;
                }
                if (value.type === constant.Dn_Draft) {
                    self.Dn_Draft = value;
                }
                if (value.type === constant.Dn_WaitingForConfirm) {
                    self.Dn_WaitingForConfirm = value;
                }
                if (value.type === constant.Dn_Approve) {
                    self.Dn_Approve = value;
                }
                if (value.type === constant.Dn_Confirm) {
                    self.Dn_Confirm = value;
                }
            });
            _.forEach(data.cl_expired, function (val) {
                if (val.type === constant.Credit_Limit_Expired) {
                    self.crel_expired = JSON.parse(val.value);
                }
                if (val.type === constant.Credit_Limit_Upcoming_Expired) {
                    self.crel_upcoming_expired = JSON.parse(val.value);
                }
            });
        });

        this.listDashboard();
    }

    listDashboard() {
        this.app.get('dashboards/list').subscribe((res: any) => {
             this.List_Po_Submit = res.po_submit;
             this.List_Po_Processing = res.po_accept;
             this.List_Po_Expired = res.po_expired;
             this.List_So_Draf = res.so_draf;
             this.List_So_Processing = res.so_open;
             this.List_So_Close = res.so_close;
             this.List_Dn_Draft = res.dn_draft;
             this.List_Dn_WaitingForConfirm = res.dn_waitingforconfirm;
             this.List_Dn_Approved = res.dn_approved;
             this.List_Dn_Confirm = res.dn_confirm;
        });
    }

    showListPoNew() {
        $('#PoNew').modal();
    }

    ListPoProcessing() {
        $('#PoProcessing').modal();
    }

    ListPoExpired() {
        $('#PoExpired').modal();
    }

    ListPoClose() {
        $('#PoClose').modal();
    }

    ListSoDraf() {
        $('#SoDraf').modal();
    }

    ListSoExpired() {
        $('#SoExpired').modal();
    }


    ListSoProcessing() {
        $('#SoProcessing').modal();
    }

    ListSoClose() {
        $('#SoClose').modal();
    }

    ListDnDraft() {
        $('#DnDraft').modal();
    }

    ListDnWaitingForConfirm() {
        $('#DnWaitingForConfirm').modal();
    }

    ListDnApproved() {
        $('#DnApproved').modal();
    }

    ListDnConfirm() {
        $('#DnConfirm').modal();
    }

}
