class Popup {
    inputField;
    dialog;
    option;
    counter = 0;

    constructor(inputField) {
        this.initialiseField(inputField);
    }

    initialiseField(inputField) {
        this.inputField = inputField;

        let inputName            = this.inputField.getAttribute('name');
        let inputMatchCountry    = inputName.match(/^configuration\[MODULE_SHIPPING_GRANDELJAYFREIGHT_(COUNTRY_[A-Z]{2})\]$/);
        let inputMatchSurcharges = inputName.match(/^configuration\[MODULE_SHIPPING_GRANDELJAYFREIGHT_(SURCHARGES_SURCHARGES)\]$/);

        if (inputMatchCountry && inputMatchCountry.length === 2) {
            this.option = inputMatchCountry[1];
        }

        if (inputMatchSurcharges && inputMatchSurcharges.length === 2) {
            this.option = inputMatchSurcharges[1];
        }

        let popup = this;
        this.inputField.addEventListener('focus', function() { popup.focus() });
        this.inputField.removeAttribute('disabled');
    }

    initialiseButtonRemove() {
        let buttonsRemove = Array.from(this.dialog.querySelectorAll('td > button[type="button"].remove'));
        let popup         = this;

        buttonsRemove.every(function(buttonRemove) {
            popup.eventButtonRemoveClick(buttonRemove);

            return true;
        });
    }
    eventButtonRemoveClick(button) {
        button.addEventListener('click', function() {
            let tr = this.closest('tr');

            tr.remove();
        });
    }

    initialiseButtonAdd() {
        let buttonAdd = this.dialog.querySelector('[data-name="add"]');

        buttonAdd.addEventListener('click', this.eventButtonAddClick);
        buttonAdd.popup = this;
    }
    eventButtonAddClick() {
        let popup        = this.popup;
        let tbody        = this.closest('tbody');
        let templateRow  = tbody.querySelector('template.row');
        let content      = templateRow.cloneNode(true).content;

        let fieldRates   = content.querySelector('[data-name="postal-rates"]');
        let buttonRemove = content.querySelector('button.remove');
        let fieldRadios  = Array.from(content.querySelectorAll('.radio [type="radio"]'));

        if (fieldRates) {
            fieldRates.addEventListener('click', this.popup.eventFieldRatesFocus);
        }

        buttonRemove.addEventListener('click', this.popup.eventButtonRemoveClick(buttonRemove));

        fieldRadios.every(function(fieldRadio) {
            let name = fieldRadio.getAttribute('name');

            fieldRadio.setAttribute('name', name  + '-' + popup.counter);

            return true;
        });

        this.popup.counter++;
        this.closest('tfoot').previousElementSibling.append(content);
    }

    initialiseButtonApply() {
        let buttonsApply = Array.from(this.dialog.querySelectorAll('[data-name="apply"]'));

        buttonsApply.every(function(buttonApply) {
            buttonApply.addEventListener('click', function() {
                let table      = this.closest('table');
                let rowsData   = Array.from(table.querySelector('tbody').children);
                let inputJSON  = [];
                let inputField = this.closest('dialog').previousElementSibling;

                rowsData.every(function(tr) {
                    if ('TR' !== tr.tagName) {
                        return true;
                    }

                    let inputs = [];
                    inputs     = inputs.concat(Array.from(tr.querySelectorAll('[name]')));
                    inputs     = inputs.concat(Array.from(tr.querySelectorAll('[data-name]')));

                    let inputJSONrow = {};

                    inputs.every(function(input) {
                        let name   = input.getAttribute('data-name');
                        let value  = input.value;
                        let isJSON;

                        if (input.type === 'radio' && !input.checked) {
                            return true;
                        }

                        try {
                            isJSON = JSON.parse(value);
                        } catch (error) {
                            isJSON = false;
                        }

                        if (Array.isArray(isJSON)) {
                            inputJSONrow[name] = isJSON;
                        } else {
                            inputJSONrow[name] = value;
                        }

                        return true;
                    });

                    if(Object.keys(inputJSONrow).length > 0){
                        inputJSON.push(inputJSONrow);
                    }

                    return true;
                });

                inputField.value = JSON.stringify(inputJSON);

                this.closest('dialog').close();
            });

            return true;
        });
    }

    initialiseButtonBulk() {
        let buttonBulk = this.dialog.querySelector('[data-name="bulk"]');

        if (buttonBulk) {
            buttonBulk.addEventListener('click', function() {
                let dialogTemplate = document.querySelector('template#grandeljayfreight_bulk');
                let dialogElement  = document.querySelector('template#grandeljayfreight_bulk + dialog');
                let option         = this.getAttribute('data-option');

                if (null === dialogElement) {
                    let dialogHTML = dialogTemplate.cloneNode(true).content;
                    dialogTemplate.after(dialogHTML);
                    dialogElement = document.querySelector('template#grandeljayfreight_bulk + dialog');

                    let buttonSave = dialogElement.querySelector('[data-name="save"]');
                    buttonSave.addEventListener('click', function() {
                        let loading = dialogElement.querySelector('.loading');
                        loading.classList.add('visible');

                        let buttonSave = this;
                        buttonSave.setAttribute('disabled', 'disabled');

                        let options = {
                            'method'  : 'POST',
                            'headers' : {
                                'Content-Type': 'application/json;charset=utf-8',
                            },
                            'body'    : JSON.stringify({
                                'action' : 'bulk',
                                'body'   : {
                                    'factor' : dialogElement.querySelector('[data-name="factor"]').value,
                                    'option' : option,
                                }
                            }),
                        };

                        fetch('/api/grandeljay/freight/v1/', options)
                        .then(response => response.json())
                        .then(success => {
                            let dialogs = Array.from(document.querySelectorAll('dialog[open]'));

                            dialogs.every(function(dialog) {
                                dialog.close();

                                return true;
                            });

                            if (success.success) {
                                alert('Success! Please wait while the page reloads to reflect the newest changes.');

                                window.location.reload(true);
                            } else {
                                alert('Failure');
                            }
                        })
                        .catch(error => {
                            console.log(error);

                            alert('Failure');
                        })
                        .finally(() => {
                            loading.classList.remove('visible');
                            buttonSave.removeAttribute('disabled');
                        });
                    });
                }

                dialogElement.showModal();
            });
        }
    }

    initialiseButtonCancel() {
        let buttonsCancel = Array.from(this.dialog.querySelectorAll('[data-name="cancel"]'));

        buttonsCancel.every(function(buttonCancel) {
            buttonCancel.addEventListener('click', function() {
                let dialog = this.closest('dialog');

                dialog.close();
            });

            return true;
        });
    }

    focus() {
        this.initialiseDialog();

        this.inputField.blur();
        this.dialog.showModal();

        this.initialiseButtonRemove();
        this.initialiseButtonAdd();
        this.initialiseButtonApply();
        this.initialiseButtonBulk();
        this.initialiseButtonCancel();
        this.initialiseFieldRates();
    }

    initialiseDialog() {
        if (!this.dialog) {
            let templateDialog = document.querySelector('#' + this.option + ' > template');

            this.inputField.after(templateDialog.cloneNode(true).content);
            this.dialog = document.querySelector('#' + this.option + ' > dialog');
        }

        let tbody = this.dialog.querySelector('template + table > tbody');
        tbody.innerHTML = '';

        let templateTr = tbody.closest('table').previousElementSibling;
        let fieldData  = JSON.parse(this.inputField.value);
        let popup      = this;

        if (Object.entries(fieldData).length > 0) {
            fieldData.every(function(entry) {
                let templateTrHTML = templateTr.cloneNode(true).content;

                for (const [key, value] of Object.entries(entry)) {
                    let trField = templateTrHTML.querySelector('[data-name="' + key + '"]');

                    if (trField) {
                        if (Array.isArray(value)) {
                            trField.value = JSON.stringify(value);
                        } else if(trField.type !== 'radio')  {
                            trField.value = value;
                        }

                        if ('surcharge-per-pallet' === key) {
                            let inputRadios = Array.from(templateTrHTML.querySelectorAll('[data-name="' + key + '"]'));

                            inputRadios.every(function(inputRadio) {
                                inputRadio.setAttribute('name', key + '-' + popup.counter);
                                inputRadio.checked = inputRadio.value === value;

                                return true;
                            })
                        }
                    }
                }

                popup.counter++;

                tbody.append(templateTrHTML);

                return true;
            });
        }
    }

    initialiseFieldRates() {
        let fieldRates = Array.from(this.dialog.querySelectorAll('[data-name="postal-rates"]'));
        let popup      = this;

        fieldRates.every(function(fieldRate) {
            fieldRate.addEventListener('focus', popup.eventFieldRatesFocus);
            fieldRate.popup = popup;

            return true;
        });
    }
    eventFieldRatesFocus() {
        this.blur();

        let dialog = this.parentElement.querySelector('dialog');

        /**
         * Create dialog
         */
        if (!dialog) {
            let templateDialog = this.parentElement.querySelector('template.postal-rates').content;

            /**
             * Button: Add
             */
            let buttonAdd = templateDialog.querySelector('[data-name="add"]');

            buttonAdd.addEventListener('click', function() {
                let dialog      = this.closest('dialog');
                let templateRow = dialog.querySelector('template.row').cloneNode(true).content;
                let tbody       = this.closest('table').querySelector('tbody');

                /** Button: Remove */
                let buttonRemove = templateRow.querySelector('td:last-of-type > button.remove');

                buttonRemove.addEventListener('click', function() {
                    this.closest('tr').remove();
                });
                /** */

                tbody.append(templateRow);
            });
            /** */

            /**
             * Button: Apply
             */
            let buttonApply = templateDialog.querySelector('[data-name="apply"]');

            buttonApply.addEventListener('click', function() {
                let trs        = Array.from(this.closest('table.rates').querySelectorAll('tbody > tr'));
                let dialog     = this.closest('dialog');
                let fieldRates = dialog.previousElementSibling;
                let inputArray = [];

                trs.every(function(tr) {
                    let inputs = [];
                    inputs     = inputs.concat(Array.from(tr.querySelectorAll('[name]')));
                    inputs     = inputs.concat(Array.from(tr.querySelectorAll('[data-name]')));

                    let inputJSON = {};

                    inputs.every(function(input) {
                        let name  = input.getAttribute('data-name');
                        let value = input.value;

                        inputJSON[name] = value;

                        return true;
                    });

                    if(Object.keys(inputJSON).length > 0){
                        inputArray.push(inputJSON);
                    }

                    return true;
                });

                fieldRates.value = JSON.stringify(inputArray);

                dialog.close();
            });
            /** */

            /**
             * Button: Cancel
             */
            let buttonCancel = templateDialog.querySelector('[data-name="cancel"]');

            buttonCancel.addEventListener('click', function() {
                let dialog = this.closest('dialog');

                dialog.close();
            });
            /** */

            /** Insert dialog element */
            this.after(templateDialog);

            dialog = this.parentElement.querySelector('dialog');
        }

        /**
         * Fill dialog
         */
        let fieldRatesJSON = this.value ? JSON.parse(this.value) : {};
        let tbody          = dialog.querySelector('table.rates > tbody');

        tbody.innerHTML = '';

        if (Object.keys(fieldRatesJSON).length > 0) {
            fieldRatesJSON.every(function(entry) {
                let templateRow = dialog.querySelector('template.row').cloneNode(true).content;

                for (const [key, value] of Object.entries(entry)) {
                    let trField = templateRow.querySelector('[data-name="' + key + '"]');
                    trField.value = value;
                }

                /** Button: Remove */
                let buttonsRemove = Array.from(templateRow.querySelectorAll('tr > td:last-of-type > button.remove'));

                buttonsRemove.every(function(buttonRemove) {
                    buttonRemove.addEventListener('click', function() {
                        this.closest('tr').remove();
                    });
                });
                /** */

                tbody.append(templateRow);

                return true;
            });
        }

        /**
         * Show dialog
         */
        dialog.showModal();
    }
}
