import {Component, HostListener, OnInit} from '@angular/core';
import {ActivatedRoute, Router, RouterStateSnapshot} from '@angular/router';
import {AppService} from '../../app.service';
import {TranslationPipe} from '../../translation.pipe';
import {Actions, ofActionSuccessful, Store} from '@ngxs/store';
import {LogOut} from '../../../store/actions/users.action';
import {AuthGuard} from '../../guard/auth.guard';
import {constant} from '../../../config';
import {UserState} from '../../../store/user.state';
import * as $ from 'jquery';

@Component({
    selector: 'ele-menu',
    templateUrl: './menu.component.html',
    styleUrls: ['./menu.component.css'],
})
export class MenuComponent implements OnInit {

    public menus;
    public activeMenu;
    public maxWidthSidebar = 768;
    public currentWidth = 768;
    public group;

    constructor(public app: AppService,
                public route: ActivatedRoute,
                private router: Router,
                private store: Store,
                private auth: AuthGuard,
                public actions$: Actions) {
    }

    ngOnInit() {
        this.store.select(UserState.getCurrentUser).subscribe((val) => {
            if (val) {
                this.group = val.is_admin;
            }
        });
        this.generateMenus();
        this.activeMenu = window.location.pathname;

    }

    logout() {
        // this.route.url.subscribe((val) => console.log(val));
        this.store.dispatch(new LogOut()).subscribe(() => {
            // this.auth.resetSourceAuth();
            this.app.setConfig(constant.WAS_LOGGED_IN, false);
            this.router.navigate(['/customer/login']);
        });
    }

    getInternalLink(item) {
        if (item.type === this.app.constant.MENU_TYPE_INTERNAL) {
            let trans = new TranslationPipe();
            let link = '/' + this.app.curLang + '/';

            if (item.cate_slug) {
                link += trans.transform(item.cate_slug);
            } else if (item.post_slug) {
                link += 'post/' + trans.transform(item.post_slug);
            } else {
                link += item.link;
            }
            return link;
        }

        return '';
    }

    @HostListener('window:resize', ['$event'])
    onResize(event) {
        this.currentWidth = event.target.innerWidth;
        if (this.currentWidth > this.maxWidthSidebar) {
            document.getElementById('mySidenav').style.width = '100%';
        } else {
            document.getElementById('mySidenav').style.width = '0';
        }
    }

    gotoUrl(url) {
        if (!url || url === '#') {
            return false;
        }
        this.activeMenu = url;
        // this.closeNav();
        this.router.navigateByUrl(url);
    }

    generateMenus() {
            this.menus = [
                {
                    text: 'Top',
                    url: '/'
                },
                {
                    text: 'Products',
                    url: '/products'
                },
                {
                    text: 'Manager orders',
                    url: '/order',
                },
                {
                    text: 'Staffs',
                    url: '/staff',
                },
                {
                    text: 'Profile',
                    url: '/profile',
                },
                // {
                //     text: 'Catalog',
                //     url: '/catalog',
                // },
            ];
    }

    openNav() {
        if (this.currentWidth < this.maxWidthSidebar) {
            document.getElementById('mySidenav').style.width = '100%';

        }
    }

    closeNav() {
        if (this.currentWidth < this.maxWidthSidebar) {
            document.getElementById('mySidenav').style.width = '0';
        }
    }


}
