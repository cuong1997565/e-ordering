import {Component, OnInit, Input, OnChanges, Output, EventEmitter} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {AppService} from '../../app.service';
import * as $ from 'jquery';

@Component({
    selector: 'ele-paginator',
    templateUrl: './paginator.component.html',
    styleUrls: ['./paginator.component.css']
})
export class PaginatorComponent implements OnInit {

    @Input() currentPage: number;
    @Input() totalPages: number;
    @Input() showNumberPages: number = 5;
    @Input('scroll') isScroll: any = true;
    @Output() clicking = new EventEmitter();
    public listPages = [];
    public startPage = 1;
    public firtPage = 1;
    private currentTotalPage;
    private endPage: number;
    private currentLink;
    private loadedPaginator = false;
    private paramQuery = 'page';

    constructor(
        public app: AppService,
        private route: ActivatedRoute,
        private router: Router
    ) {
    }

    ngOnInit() {
        if (!this.totalPages) {
            return;
        }
        this.currentTotalPage = this.totalPages;
        if (!this.currentPage) {
            this.currentPage = 1;
        }
        this.currentLink = window.location.href;
        this.generateStartAndEndPage();
    }

    ngDoCheck(val) {
        if (this.totalPages && this.currentTotalPage != this.totalPages) {
            this.loadedPaginator = false;
        }
        if (this.totalPages && this.currentPage && !this.loadedPaginator) {
            this.loadedPaginator = true;
            this.currentLink = window.location.href;
            this.generateStartAndEndPage();
        }
    }

    generateStartAndEndPage() {
        let startPage = this.currentPage - (Math.floor(this.showNumberPages / 2));
        this.startPage = startPage > 1 ? startPage : 1;

        let endPage = this.startPage + this.showNumberPages - 1;
        if (endPage < this.totalPages) {
            this.endPage = endPage;
        } else {
            this.endPage = this.totalPages;
        }
        if (this.endPage - this.showNumberPages + 1 >= 1 && this.endPage - this.showNumberPages + 1 < this.startPage) {
            this.startPage = this.endPage - this.showNumberPages + 1;
        }
        if (this.endPage - this.showNumberPages <= 1) {
            this.startPage = 1;
        }
        this.listPages = [];
        for (let i = this.startPage; i <= this.endPage; i++) {
            this.listPages.push(i);
        }
    }

    gotoPage(page) {
        this.clicking.emit(true);
        this.currentPage = page;
        this.generateStartAndEndPage();
        let currentUrl = window.location.pathname;
        let newQueryParams = {};
        let currentQueries = new URL(window.location.href);

        if (page > 1) {
            currentQueries.searchParams.set(this.paramQuery, page);
        } else {
            currentQueries.searchParams.delete(this.paramQuery);
        }
        let pathName = currentQueries.pathname;
        if (this.isScroll) {
            $('html, body').animate({scrollTop: 0}, 'slow');
        }
        if (currentQueries.pathname.indexOf('/admin') === 0) {
            pathName = currentQueries.pathname.replace('/admin', '');
        }
        this.router.navigateByUrl(pathName + currentQueries.search);
    }

    gotoNextPage(currentPage) {
        this.clicking.emit(true);
        let nextPage = parseInt(currentPage) + 1;
        if (nextPage > this.totalPages) {
            nextPage = this.totalPages;
        }
        this.gotoPage(nextPage);
    }

    gotoPrevPage(currentPage) {
        this.clicking.emit(true);
        let prevPage = parseInt(currentPage) - 1;
        if (prevPage < 1) {
            prevPage = 1;
        }
        this.gotoPage(prevPage);
    }
}
