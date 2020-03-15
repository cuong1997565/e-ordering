import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ShortcodePipe } from "./shortcode.pipe";
import { ShortcodeComponent } from "./element/shortcode/shortcode.component";
import { FooterComponent } from "./element/footer/footer.component";
import { HeaderComponent } from "./element/header/header.component";
import { LanguageSelectorComponent } from "./element/language-selector/language-selector.component";
import { PaginatorComponent } from "./element/paginator/paginator.component";
import { MenuComponent } from "./element/menu/menu.component";
import { UserInfoComponent } from './element/member/info.component';
import { UserLogoutComponent } from './element/member/logout.component';
import { ListPipe } from "./list.pipe";
import { FlashComponent} from "./element/flash/flash.component";
import { BreadcrumbComponent } from './element/breadcrumb/breadcrumb.component';
import { ProfileMenuComponent } from "./element/member/profile-menu.component";
import { RouterModule } from "@angular/router";
import { TranslationPipe } from "./translation.pipe";
import {SelectDropdownComponent} from './element/select/select-dropdown.component';

@NgModule({
    imports: [
        CommonModule,
        RouterModule
    ],
    declarations:
    [
        TranslationPipe,
        ListPipe,
        ShortcodePipe,
        ShortcodeComponent,
        FooterComponent,
        HeaderComponent,
        SelectDropdownComponent,
        LanguageSelectorComponent,
        PaginatorComponent,
        MenuComponent,
        UserInfoComponent,
        UserLogoutComponent,
        FlashComponent,
        BreadcrumbComponent,
        ProfileMenuComponent
    ],
    exports :
    [
        TranslationPipe,
        ListPipe,
        ShortcodePipe,
        ShortcodeComponent,
        FooterComponent,
        HeaderComponent,
        LanguageSelectorComponent,
        PaginatorComponent,
        MenuComponent,
        UserInfoComponent,
        UserLogoutComponent,
        FlashComponent,
        BreadcrumbComponent,
        SelectDropdownComponent
    ]
})

export class ShareModule { }
