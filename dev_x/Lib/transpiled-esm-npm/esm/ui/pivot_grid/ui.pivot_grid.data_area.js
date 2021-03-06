import $ from '../../core/renderer';
import { AreaItem } from './ui.pivot_grid.area_item';
import { nativeScrolling } from '../../core/utils/support';
import { calculateScrollbarWidth } from './utils/calculate_scrollbar_width';
var PIVOTGRID_AREA_CLASS = 'dx-pivotgrid-area';
var PIVOTGRID_AREA_DATA_CLASS = 'dx-pivotgrid-area-data';
var PIVOTGRID_TOTAL_CLASS = 'dx-total';
var PIVOTGRID_GRAND_TOTAL_CLASS = 'dx-grandtotal';
var PIVOTGRID_ROW_TOTAL_CLASS = 'dx-row-total';
export var DataArea = AreaItem.inherit({
  _getAreaName: function _getAreaName() {
    return 'data';
  },
  _createGroupElement: function _createGroupElement() {
    return $('<div>').addClass(PIVOTGRID_AREA_CLASS).addClass(PIVOTGRID_AREA_DATA_CLASS).css('borderTopWidth', 0);
  },
  _applyCustomStyles: function _applyCustomStyles(options) {
    var cell = options.cell;
    var classArray = options.classArray;

    if (cell.rowType === 'T' || cell.columnType === 'T') {
      classArray.push(PIVOTGRID_TOTAL_CLASS);
    }

    if (cell.rowType === 'GT' || cell.columnType === 'GT') {
      classArray.push(PIVOTGRID_GRAND_TOTAL_CLASS);
    }

    if (cell.rowType === 'T' || cell.rowType === 'GT') {
      classArray.push(PIVOTGRID_ROW_TOTAL_CLASS);
    }

    if (options.rowIndex === options.rowsCount - 1) {
      options.cssArray.push('border-bottom: 0px');
    }

    this.callBase(options);
  },
  _moveFakeTable: function _moveFakeTable(scrollPos) {
    this._moveFakeTableHorizontally(scrollPos.x);

    this._moveFakeTableTop(scrollPos.y);

    this.callBase();
  },
  renderScrollable: function renderScrollable() {
    this._groupElement.dxScrollable({
      useNative: this.getUseNativeValue(),
      useSimulatedScrollbar: false,
      rtlEnabled: this.component.option('rtlEnabled'),
      bounceEnabled: false,
      updateManually: true
    });
  },
  getUseNativeValue: function getUseNativeValue() {
    var {
      useNative
    } = this.component.option('scrolling');
    return useNative === 'auto' ? !!nativeScrolling : !!useNative;
  },
  getScrollbarWidth: function getScrollbarWidth() {
    return this.getUseNativeValue() ? calculateScrollbarWidth() : 0;
  },
  updateScrollableOptions: function updateScrollableOptions(_ref) {
    var {
      direction,
      rtlEnabled
    } = _ref;

    var scrollable = this._getScrollable();

    scrollable.option('useNative', this.getUseNativeValue());
    scrollable.option({
      direction,
      rtlEnabled
    });
  },
  getScrollableDirection: function getScrollableDirection(horizontal, vertical) {
    if (horizontal && !vertical) {
      return 'horizontal';
    } else if (!horizontal && vertical) {
      return 'vertical';
    }

    return 'both';
  },
  reset: function reset() {
    this.callBase();

    if (this._virtualContent) {
      this._virtualContent.parent().css('height', 'auto');
    }
  },
  setVirtualContentParams: function setVirtualContentParams(params) {
    this.callBase(params);

    this._virtualContent.parent().css('height', params.height);

    this._setTableCss({
      top: params.top,
      left: params.left
    });
  }
});