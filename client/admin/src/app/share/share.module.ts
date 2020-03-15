import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {TranslationPipe} from './translation.pipe';
import {FooterComponent} from './element/footer/footer.component';
import {HeaderComponent} from './element/header/header.component';
import {LanguageSelectorComponent} from './element/language-selector/language-selector.component';
import {PaginatorComponent} from './element/paginator/paginator.component';
import {MenuComponent} from './element/menu/menu.component';
import {UserInfoComponent} from './element/user/info.component';
import {UserLogoutComponent} from './element/user/logout.component';
import {ListPipe} from './list.pipe';
import {ActiveComponent} from './element/active/active.component';
import {FlashComponent} from './element/flash/flash.component';
import {BreadcrumbComponent} from './element/breadcrumb/breadcrumb.component';
import {ProfileMenuComponent} from './element/user/profile-menu.component';
import {RouterModule} from '@angular/router';
import {UploadComponent} from './element/upload/upload.component';
import {UploadService} from './element/upload/upload.service';
import {MediaSelectorComponent} from './element/media-selector/media-selector.component';
import {LanguageInputComponent} from './element/language-input/language-input.component';
import {EditorComponent} from './element/editor/editor.component';
import {CustomInputComponent} from './element/custom-input/custom-input.component';
import {ImageSelectorComponent} from './element/image-selector/image-selector.component';
import {AreaSelectorComponent} from './element/area-selector/area-selector.component';
import {CommuneSelectorComponent} from './element/area-selector/commune-selector/commune-selector.component';
import {DistrictSelectorComponent} from './element/area-selector/district-selector/district-selector.component';
import {ProvinceSelectorComponent} from './element/area-selector/province-selector/province-selector.component';
import {VillageSelectorComponent} from './element/area-selector/village-selector/village-selector.component';
import {DatexPipe} from './datex.pipe';

@NgModule({
    imports: [
        CommonModule,
        RouterModule
    ],
    declarations:
        [
            TranslationPipe,
            ListPipe,
            FooterComponent,
            HeaderComponent,
            LanguageSelectorComponent,
            PaginatorComponent,
            MenuComponent,
            UserInfoComponent,
            UserLogoutComponent,
            ActiveComponent,
            FlashComponent,
            BreadcrumbComponent,
            ProfileMenuComponent,
            UploadComponent,
            MediaSelectorComponent,
            LanguageInputComponent,
            EditorComponent,
            CustomInputComponent,
            ImageSelectorComponent,
            AreaSelectorComponent,
            CommuneSelectorComponent,
            DistrictSelectorComponent,
            ProvinceSelectorComponent,
            VillageSelectorComponent,
            DatexPipe
        ],
    exports:
        [
            TranslationPipe,
            ListPipe,
            FooterComponent,
            HeaderComponent,
            LanguageSelectorComponent,
            PaginatorComponent,
            MenuComponent,
            UserInfoComponent,
            UserLogoutComponent,
            ActiveComponent,
            FlashComponent,
            BreadcrumbComponent,
            ProfileMenuComponent,
            UploadComponent,
            MediaSelectorComponent,
            LanguageInputComponent,
            EditorComponent,
            CustomInputComponent,
            ImageSelectorComponent,
            AreaSelectorComponent,
            CommuneSelectorComponent,
            DistrictSelectorComponent,
            ProvinceSelectorComponent,
            VillageSelectorComponent,
            DatexPipe
        ],
    providers: [UploadService]
})

export class ShareModule {
}
