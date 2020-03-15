import { Component, Input, OnInit } from '@angular/core';
import { AppService } from "../../app.service";
import { UploadService } from "./upload.service";
import * as $ from 'jquery';

@Component({
    selector: 'ele-upload',
    templateUrl: './upload.component.html',
    styleUrls: ['./upload.component.css']
})

export class UploadComponent implements OnInit {

    @Input() public fieldName:string;
    @Input() public filePath:string;
    @Input() public extensions:string;
    @Input() public size:number;
    @Input() public confirm:boolean;
    @Input() public required = 'false';

    public validateErrors:any;
    public fileConfirm:any;

    constructor(
        private app:AppService,
        private uploadService: UploadService
    ) { }


    ngOnInit() {
    }


    changeInputFile(event) {
        this.validateErrors = [];
        let fileToUpload = this.app.filesUpload;
        let dataConfirm = [];
        let files = event.target.files;
        let thisComponent = this;
        let fileValidate = {
            required: this.required,
            size: this.size,
            extensions: this.extensions.split('|')
        };
        let validateErrors = this.validateFile(files, fileValidate);
        if (typeof validateErrors['type'] !== 'undefined') {
            fileToUpload = false;
            this.validateErrors = validateErrors;
            $('.input_'+this.fieldName).val('');
            $('.confirm_'+this.fieldName).attr('src', this.app.constant.FILE_URL+this.filePath);
        } else {
            fileToUpload = files[0];
            this.getImageSource(this.fieldName, fileToUpload, function (data) {
                $('.confirm_'+thisComponent.fieldName).attr('src', data);
            });
        }
        this.uploadService.setDataFile(this.fieldName, fileToUpload);
    }

    validateFile(files, validateRulesCustom:{}) {
        let validateRules = {};
        Object.assign(validateRules, this.app.constant.fileValidateDefault, validateRulesCustom);
        let maxSize = validateRules['size'];
        let extensions = validateRules['extensions'];
        let validateErrors = [];

        if (!files.length) {
            validateErrors['type'] = 'required';
            validateErrors['message'] = 'File is can not empty';
            return validateErrors;
        }

        for(let file of files) {
            let extensionFile = file.name.split('.').pop();

            if (extensions.indexOf(extensionFile.toLowerCase()) === -1) {
                validateErrors['type'] = 'extension';
                validateErrors['message'] = 'Extension is invalid';
                break;
            }
            if (parseInt(file.size) > parseInt(maxSize) * 1024) {
                validateErrors['type'] = 'size';
                validateErrors['message'] = 'File size is invalid';
                break;
            }
        }

        return validateErrors;
    }

    getImageSource(field, file, callback) {
        if (file.type.indexOf('image') !== -1) {

            let reader = new FileReader();
            reader.onload = function (e) {
                let result = e.target['result'];
                callback(result);
            };
            reader.readAsDataURL(file);

            return;
        } else {
            let fileSource = this.app.constant.BASE_WEB + 'default/file-default.png';
            $('.confirm_'+field).attr('src', fileSource);
            callback(fileSource);
        }
    }

}
