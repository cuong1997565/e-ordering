import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
    name: 'list'
})

export class ListPipe implements PipeTransform {
    transform(input, defaultItem?: string, keyField: string = 'id', valField: string = 'name', codeField: string = 'code'): any {
        let keys = [];

        if (defaultItem) {
            keys.push({key: null, value: `--- ${defaultItem} ---`});
        }

        // Support both type: array & object
        if (Array.isArray(input)) {
            for (let i in input) {
                keys.push({key: input[i][keyField], value: input[i][valField], code: input[i][codeField]});
            }
        } else {
            for (let key in input) {
                keys.push({key, value: input[key]});
            }
        }

        return keys;
    }
}
