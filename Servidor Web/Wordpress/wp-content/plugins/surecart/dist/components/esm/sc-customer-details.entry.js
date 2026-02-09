import { r as registerInstance, h, a as getElement } from './index-745b6bec.js';
import { c as countryChoices } from './address-fb437e60.js';
import { z as zones } from './tax-a03623ca.js';
import './add-query-args-0e2a8393.js';

const scCustomerDetailsCss = "";
const ScCustomerDetailsStyle0 = scCustomerDetailsCss;

const ScCustomerDetails = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
        this.heading = undefined;
        this.editLink = undefined;
        this.customer = undefined;
        this.loading = undefined;
        this.error = undefined;
        this.countryChoices = undefined;
    }
    componentWillLoad() {
        this.initCountryChoices();
    }
    async initCountryChoices() {
        this.countryChoices = await countryChoices();
    }
    renderContent() {
        var _a, _b, _c, _d, _e, _f, _g, _h;
        if (this.loading) {
            return this.renderLoading();
        }
        if (!this.customer) {
            return this.renderEmpty();
        }
        return (h("sc-card", { "no-padding": true }, h("sc-stacked-list", null, !!((_a = this === null || this === void 0 ? void 0 : this.customer) === null || _a === void 0 ? void 0 : _a.name) && (h("sc-stacked-list-row", { style: { '--columns': '3' }, mobileSize: 480 }, h("div", null, h("strong", null, wp.i18n.__('Billing Name', 'surecart'))), h("div", null, (_b = this.customer) === null || _b === void 0 ? void 0 : _b.name), h("div", null))), !!((_c = this === null || this === void 0 ? void 0 : this.customer) === null || _c === void 0 ? void 0 : _c.email) && (h("sc-stacked-list-row", { style: { '--columns': '3' }, mobileSize: 480 }, h("div", null, h("strong", null, wp.i18n.__('Billing Email', 'surecart'))), h("div", null, (_d = this.customer) === null || _d === void 0 ? void 0 : _d.email), h("div", null))), !!Object.keys(((_e = this === null || this === void 0 ? void 0 : this.customer) === null || _e === void 0 ? void 0 : _e.shipping_address_display) || {}).length && this.renderAddress(wp.i18n.__('Shipping Address', 'surecart'), this.customer.shipping_address_display), !!Object.keys((_f = this.customer) === null || _f === void 0 ? void 0 : _f.billing_address_display).length && this.renderAddress(wp.i18n.__('Billing Address', 'surecart'), this.customer.billing_address_display), !!((_g = this === null || this === void 0 ? void 0 : this.customer) === null || _g === void 0 ? void 0 : _g.phone) && (h("sc-stacked-list-row", { style: { '--columns': '3' }, mobileSize: 480 }, h("div", null, h("strong", null, wp.i18n.__('Phone', 'surecart'))), h("div", null, (_h = this.customer) === null || _h === void 0 ? void 0 : _h.phone), h("div", null))), (() => {
            var _a, _b, _c, _d;
            const { number_type, number } = ((_a = this.customer) === null || _a === void 0 ? void 0 : _a.tax_identifier) || {};
            if (!number || !number_type)
                return;
            const label = ((_b = zones === null || zones === void 0 ? void 0 : zones[number_type]) === null || _b === void 0 ? void 0 : _b.label) || wp.i18n.__('Tax Id', 'surecart');
            const isInvalid = ((_d = (_c = this.customer) === null || _c === void 0 ? void 0 : _c.tax_identifier) === null || _d === void 0 ? void 0 : _d[`valid_${number_type}`]) === false;
            return (h("sc-stacked-list-row", { style: { '--columns': '3' }, mobileSize: 480 }, h("div", null, h("strong", null, label)), h("div", null, number, " ", isInvalid && h("sc-tag", { type: "warning" }, wp.i18n.__('Invalid', 'surecart'))), h("div", null)));
        })())));
    }
    renderAddress(label = 'Address', address) {
        return (h("sc-stacked-list-row", { style: { '--columns': '3' }, mobileSize: 480 }, h("div", null, h("strong", null, label)), h("div", { style: { whiteSpace: 'pre-line' } }, address), h("div", null)));
    }
    renderEmpty() {
        return (h("div", null, h("sc-divider", { style: { '--spacing': '0' } }), h("slot", { name: "empty" }, h("sc-empty", { icon: "user" }, wp.i18n.__("You don't have any billing information.", 'surecart')))));
    }
    renderLoading() {
        return (h("sc-card", { "no-padding": true }, h("sc-stacked-list", null, h("sc-stacked-list-row", { style: { '--columns': '2' }, "mobile-size": 0 }, h("div", { style: { padding: '0.5em' } }, h("sc-skeleton", { style: { width: '30%', marginBottom: '0.75em' } }), h("sc-skeleton", { style: { width: '20%', marginBottom: '0.75em' } }), h("sc-skeleton", { style: { width: '40%' } }))))));
    }
    render() {
        var _a, _b, _c;
        return (h("sc-dashboard-module", { key: '4182a16cc0743107c022cc76a5021261e08ad14a', exportparts: "base, heading, heading-text, heading-title, heading-description", class: "customer-details", error: this.error }, h("span", { key: '6b43da52c31e1ef81f2b8bc0865f34e287f0fa3c', slot: "heading" }, this.heading || wp.i18n.__('Billing Details', 'surecart'), ' ', !!((_a = this === null || this === void 0 ? void 0 : this.customer) === null || _a === void 0 ? void 0 : _a.id) && !((_b = this === null || this === void 0 ? void 0 : this.customer) === null || _b === void 0 ? void 0 : _b.live_mode) && (h("sc-tag", { key: '80a09b0b70c810e451967ac110152ff348cca157', exportparts: "base:test-tag__base, content:test-tag__content", type: "warning", size: "small" }, wp.i18n.__('Test', 'surecart')))), !!this.editLink && !!((_c = this.customer) === null || _c === void 0 ? void 0 : _c.id) && (h("sc-button", { key: '49f1499b84fa34c20b5fb6be7413db7fa2277d3b', exportparts: "base:button__base, label:button__label, prefix:button__prefix", type: "link", href: this.editLink, slot: "end" }, h("sc-icon", { key: 'a45e0a40efcde4255e9fee7515f13cedc91fd294', name: "edit-3", slot: "prefix" }), wp.i18n.__('Update', 'surecart'))), this.renderContent()));
    }
    get el() { return getElement(this); }
};
ScCustomerDetails.style = ScCustomerDetailsStyle0;

export { ScCustomerDetails as sc_customer_details };

//# sourceMappingURL=sc-customer-details.entry.js.map