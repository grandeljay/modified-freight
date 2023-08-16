<?php

/**
 * Freight
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 */

namespace Grandeljay\Freight\Configuration;

class Field
{
    public static function initialiseGroup(string $heading, string $option): string
    {
        $option_parts = explode('_', $option);
        $state        = end($option_parts);

        ob_start();

        switch ($state) {
            case 'START':
                ?>
                <details>
                    <summary><?= decode_htmlentities($heading) ?></summary>
                    <div>
                <?php
                break;

            case 'END':
                ?>
                    </div>
                </details>
                <?php
                break;
        }

        return ob_get_clean();
    }

    public static function initialiseFieldCountry(string $value, string $option): string
    {
        $value                      = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
        $entries                    = json_decode($value, true) ?? array();
        $option_without_module_name = substr(
            $option,
            strlen(\grandeljayfreight::NAME) + 1,
        );

        $html          = '';
        $field_country = xtc_draw_input_field(
            'configuration[' . $option . ']',
            $value,
            'disabled="disabled"'
        );

        ob_start();
        ?>
        <div id="<?= $option_without_module_name ?>">
            <?= $field_country ?>

            <template>
                <dialog>
                    <div class="modulbox">
                        <table class="contentTable">
                            <tbody>
                                <tr class="infoBoxHeading">
                                    <td class="infoBoxHeading">
                                        <div class="infoBoxHeadingTitle"><b><?= constant($option . '_TITLE') ?></b></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="contentTable">
                            <tbody>
                                <tr class="infoBoxContent">
                                    <td class="infoBoxContent">
                                        <template class="row">
                                            <tr>
                                                <td>
                                                    <input type="text" pattern="\d{5}" data-name="postal-from" />
                                                </td>
                                                <td>
                                                    <input type="text" pattern="\d{5}" data-name="postal-to" />
                                                </td>
                                                <td>
                                                    <input type="text" data-name="postal-rates" />

                                                    <template class="postal-rates">
                                                        <dialog style="width: 543px;">
                                                            <div class="modulbox">
                                                                <table class="contentTable">
                                                                    <tbody>
                                                                        <tr class="infoBoxHeading">
                                                                            <td class="infoBoxHeading">
                                                                                <div class="infoBoxHeadingTitle"><b><?= constant(\grandeljayfreight::NAME . '_POSTAL_RATES_TITLE') ?></b></div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                                <table class="contentTable">
                                                                    <tbody>
                                                                        <tr class="infoBoxContent">
                                                                            <td class="infoBoxContent">
                                                                                <template class="row">
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="number" data-name="weight-max" />
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="number" data-name="weight-costs" />
                                                                                        </td>
                                                                                        <td>
                                                                                            <button type="button" class="remove">
                                                                                                <img src="images/icons/cross.gif" />
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>
                                                                                </template>

                                                                                <table class="rates">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <strong><?= constant(\grandeljayfreight::NAME . '_POSTAL_RATES_WEIGHT_MAX_TITLE') ?></strong><br />
                                                                                                <p><?= constant(\grandeljayfreight::NAME . '_POSTAL_RATES_WEIGHT_MAX_DESC') ?></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <strong><?= constant(\grandeljayfreight::NAME . '_POSTAL_RATES_WEIGHT_COSTS_TITLE') ?></strong><br />
                                                                                                <p><?= constant(\grandeljayfreight::NAME . '_POSTAL_RATES_WEIGHT_COSTS_DESC') ?></p>
                                                                                            </td>
                                                                                            <td></td>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody></tbody>
                                                                                    <tfoot>
                                                                                        <tr>
                                                                                            <td colspan="3" class="align-center">
                                                                                                <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_ADD') ?>" data-name="add" />
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3">
                                                                                                <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_APPLY') ?>" data-name="apply" />
                                                                                                <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_CANCEL') ?>" data-name="cancel" />
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>

                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </dialog>
                                                    </template>
                                                </td>
                                                <td>
                                                    <input type="number" step="any" data-name="postal-per-kg" />
                                                </td>
                                                <td>
                                                    <button type="button" class="remove">
                                                        <img src="images/icons/cross.gif" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>

                                        <table class="country">
                                            <thead>
                                                <tr>
                                                    <td>
                                                        <strong><?= constant(\grandeljayfreight::NAME . '_POSTAL_FROM_TITLE') ?></strong><br />
                                                        <p><?= constant(\grandeljayfreight::NAME . '_POSTAL_FROM_DESC') ?></p>
                                                    </td>
                                                    <td>
                                                        <strong><?= constant(\grandeljayfreight::NAME . '_POSTAL_TO_TITLE') ?></strong><br />
                                                        <p><?= constant(\grandeljayfreight::NAME . '_POSTAL_TO_DESC') ?></p>
                                                    </td>
                                                    <td>
                                                        <strong><?= constant(\grandeljayfreight::NAME . '_POSTAL_RATES_TITLE') ?></strong><br />
                                                        <p><?= constant(\grandeljayfreight::NAME . '_POSTAL_RATES_DESC') ?></p>
                                                    </td>
                                                    <td>
                                                        <strong><?= constant(\grandeljayfreight::NAME . '_POSTAL_PER_KG_TITLE') ?></strong><br />
                                                        <p><?= constant(\grandeljayfreight::NAME . '_POSTAL_PER_KG_DESC') ?></p>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="align-center">
                                                        <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_ADD') ?>" data-name="add" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_APPLY') ?>" data-name="apply" />
                                                        <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_BULK') ?>" data-name="bulk" data-option="<?= $option ?>" />
                                                        <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_CANCEL') ?>" data-name="cancel" />
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </dialog>
            </template>
        </div>
        <?php
        $html .= ob_get_clean();

        return $html;
    }

    public static function initialiseFieldSurcharges(string $value, string $option): string
    {
        $value                      = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
        $entries                    = json_decode($value, true) ?? array();
        $name                       = \grandeljayfreight::NAME;
        $option_without_module_name = substr(
            $option,
            strlen($name) + 1,
        );

        $html             = '';
        $field_surcharges = xtc_draw_input_field(
            'configuration[' . $option . ']',
            $value,
            'disabled="disabled"'
        );

        ob_start();
        ?>
        <div id="<?= $option_without_module_name ?>">
            <?= $field_surcharges ?>

            <template>
                <dialog>
                    <div class="modulbox">
                        <table class="contentTable">
                            <tbody>
                                <tr class="infoBoxHeading">
                                    <td class="infoBoxHeading">
                                        <div class="infoBoxHeadingTitle"><b><?= constant($option . '_TITLE') ?></b></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="contentTable">
                            <tbody>
                                <tr class="infoBoxContent">
                                    <td class="infoBoxContent">
                                        <template class="row">
                                            <tr>
                                                <td>
                                                    <input type="text" data-name="surcharge-name" />
                                                </td>
                                                <td>
                                                    <input type="number" data-name="surcharge-amount" />
                                                </td>
                                                <td>
                                                    <select data-name="surcharge-type">
                                                        <option value="surcharge-fixed"><?= constant($name . '_SURCHARGES_SURCHARGE_TYPE_FIXED') ?></option>
                                                        <option value="surcharge-percent"><?= constant($name . '_SURCHARGES_SURCHARGE_TYPE_PERCENT') ?></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="surcharge-per-pallet" data-name="surcharge-per-pallet" value="true" />
                                                            <div><?= constant('CFG_TXT_YES') ?></div>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="surcharge-per-pallet" data-name="surcharge-per-pallet" value="false" checked />
                                                            <div><?= constant('CFG_TXT_NO') ?></div>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="remove">
                                                        <img src="images/icons/cross.gif" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>

                                        <table class="surcharges">
                                            <thead>
                                                <tr>
                                                    <td>
                                                        <strong><?= constant($name . '_SURCHARGES_SURCHARGE_NAME_TITLE') ?></strong><br />
                                                        <p><?= constant($name . '_SURCHARGES_SURCHARGE_NAME_DESC') ?></p>
                                                    </td>
                                                    <td>
                                                        <strong><?= constant($name . '_SURCHARGES_SURCHARGE_AMOUNT_TITLE') ?></strong><br />
                                                        <p><?= constant($name . '_SURCHARGES_SURCHARGE_AMOUNT_DESC') ?></p>
                                                    </td>
                                                    <td>
                                                        <strong><?= constant($name . '_SURCHARGES_SURCHARGE_TYPE_TITLE') ?></strong><br />
                                                        <p><?= constant($name . '_SURCHARGES_SURCHARGE_TYPE_DESC') ?></p>
                                                    </td>
                                                    <td>
                                                        <strong><?= constant($name . '_SURCHARGES_SURCHARGE_PER_PALETT_TITLE') ?></strong><br />
                                                        <p><?= constant($name . '_SURCHARGES_SURCHARGE_PER_PALETT_DESC') ?></p>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="align-center">
                                                        <input type="button" class="button" value="<?= constant($name . '_BUTTON_ADD') ?>" data-name="add" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <input type="button" class="button" value="<?= constant($name . '_BUTTON_APPLY') ?>" data-name="apply" />
                                                        <input type="button" class="button" value="<?= constant($name . '_BUTTON_CANCEL') ?>" data-name="cancel" />
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </dialog>
            </template>
        </div>
        <?php
        $html .= ob_get_clean();

        return $html;
    }
}
