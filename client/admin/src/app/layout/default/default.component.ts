import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {AppService} from '../../share/app.service';
import '../../../assets/js/smartadmin.js';
import datepickerFactory from 'jquery-datepicker';
import datepickerENFactory from 'jquery-datepicker/i18n/jquery.ui.datepicker-ja';


declare var initApp: any;
declare var $;

@Component({
    selector: 'app-layout-default',
    templateUrl: './default.component.html',
    styleUrls: ['./default.component.css']
})
export class DefaultLayoutComponent implements OnInit {

    constructor(private app: AppService, private route: ActivatedRoute) {
    }

    ngOnInit() {
        this.route.data.subscribe((res: any) => {
            this.app.curUser = res.profile.data;
            this.app.langResolve(res.lang.data);
            this.app.appResolve(res.app);
        });
        datepickerFactory($);
        datepickerENFactory($);
        $.datepicker.setDefaults($.datepicker.regional['']);
    }

    ngAfterViewInit() {
        // This need to load every time the layout loaded
        initApp.leftNav();
        initApp.domReadyMisc();

        this.initSortableTable();
    }

    initSortableTable() {
        if ($('.sortTable').length) {
            let self = this;
            setTimeout(function () {
                $('.sortTable tbody').sortable({
                    items: 'tr',
                    placeholder: 'sortable-placeholder',
                    update: function (event, ui) {
                        let dataSort = {};
                        let table = $('.sortTable').attr('data-table');
                        if (!table) {
                            alert('Please add attribute data-table to table(.sortTable)');
                            return false;
                        }
                        dataSort['table'] = table;
                        let data = [];
                        $('.sortTable tbody').find('tr').each(function (i) {
                            let id = $(this).attr('data-id');
                            if (!id) {
                                alert('Please add attribute data-id to tr');
                                return false;
                            }
                            data.push({'id': id, 'order': i});
                        });
                        dataSort['data'] = data;
                        self.app.post('update-order', dataSort).subscribe((res: any) => {
                            if ($('.sortTable').find('.td-order').length) {
                                $('.sortTable').find('.td-order').each(function (i) {
                                    $(this).html(i);
                                });
                            }
                        });
                    },
                    start: function (event, ui) {
                        $('.sortTable').addClass('sort_parent');
                    }
                });
            }, 1000);

        }
    }

    ngDoCheck() {
        if (!$('#jarviswidget-fullscreen-mode').length) {
            $('body').removeClass('nooverflow');
        }
    }
}
