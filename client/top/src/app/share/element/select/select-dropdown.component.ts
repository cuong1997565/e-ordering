import {
    AfterViewInit,
    ChangeDetectorRef,
    Component,
    EmbeddedViewRef,
    EventEmitter,
    Input,
    NgZone,
    OnInit,
    Output,
    TemplateRef,
    ViewContainerRef
} from '@angular/core';
import * as $ from 'jquery';
import Popper from 'popper.js';
import {fromEvent} from 'rxjs';
import {filter, take, takeUntil} from 'rxjs/operators';
import * as _ from 'lodash';

@Component({
    selector: 'ele-select-dropdown',
    templateUrl: './select-dropdown.component.html',
    styleUrls: ['./select-dropdown.component.css']
})
export class SelectDropdownComponent implements OnInit {
    @Input() selectedId;
    @Input() defaultLabel = 'Select...';
    @Input() labelKey = 'name';
    @Input() labelForNone = 'name';
    @Input() idKey = 'id';
    @Input() options = [];
    @Input('disabled') isDisabled: any = false;
    @Input() model;
    @Input() list = {};
    @Output() selectChange = new EventEmitter();
    private keyByOptions = {};
    public originalOptions = {};
    private popperRef: Popper;
    private view: EmbeddedViewRef<any>;
    private length;
    @Output() closed = new EventEmitter();

    constructor(private vcr: ViewContainerRef, private zone: NgZone, private cdr: ChangeDetectorRef) {
    }

    ngOnInit() {
        const fakeSelect = {}
        fakeSelect[this.idKey] = null;
        fakeSelect[this.labelKey] = this.defaultLabel;
        const fake = []
        fake.push(fakeSelect);
        this.options = fake.concat(this.options);
        const idKey = this.idKey;
        this.keyByOptions = _.keyBy(this.options, function (option) {
            return option[idKey];
        });
        this.length = Object.keys(this.options).length;
        if (this.selectedId !== undefined && this.selectedId !== '') {
            for (const index in this.options) {
                // tslint:disable-next-line:triple-equals
                if (this.options[index][this.idKey] == this.selectedId) {
                    this.model = this.options[index];
                }
            }
        }
    }

    get isOpen() {
        return !!this.popperRef;
    }

    open(dropdownTpl: TemplateRef<any>, origin: HTMLElement) {
        if (!this.view) {
            this.view = this.vcr.createEmbeddedView(dropdownTpl);
            const dropdown = this.view.rootNodes[0];
            document.body.appendChild(dropdown);
            dropdown.style.width = `${origin.offsetWidth}px`;
            this.zone.runOutsideAngular(() => {
                this.popperRef = new Popper(origin, dropdown, {
                    removeOnDestroy: true
                });
                setTimeout(() => {
                    if (dropdown.querySelector('.active')) {
                        dropdown.scrollTop = dropdown.querySelector('.active').offsetTop - 10;
                    }
                }, 50);

                this.popperRef.scheduleUpdate();
            });
            this.handleClickOutside();
        } else {
            this.close();
        }
    }

    get label() {
        return this.selectedId ? this.keyByOptions[this.selectedId][this.labelKey] : this.defaultLabel;
    }

    sortNull() {
    }

    select(option) {
        this.selectedId = option[this.idKey];
        this.model = option;
        this.selectChange.emit(option[this.idKey]);
    }

    isActive(option) {
        if (!this.selectedId) {
            return false;
        }

        // tslint:disable-next-line:triple-equals
        return option[this.idKey] == this.selectedId;
    }

    private handleClickOutside() {
        fromEvent(document, 'click')
            .pipe(
                filter(({target}) => {
                    const origin = this.popperRef.reference as HTMLElement;
                    return origin.contains(target as HTMLElement) === false;
                }),
                takeUntil(this.closed)
            )
            .subscribe(() => {
                this.close();
                this.cdr.detectChanges();
            });
    }

    close() {
        this.closed.emit();
        this.popperRef.destroy();
        this.view.destroy();
        this.view = null;
        this.popperRef = null;
    }
}
