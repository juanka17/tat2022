"use strict";

exports.contextMenuModule = void 0;

var _renderer = _interopRequireDefault(require("../../core/renderer"));

var _element = require("../../core/element");

var _common = require("../../core/utils/common");

var _iterator = require("../../core/utils/iterator");

var _uiGrid_core = _interopRequireDefault(require("./ui.grid_core.modules"));

var _context_menu = _interopRequireDefault(require("../context_menu"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var CONTEXT_MENU = 'dx-context-menu';
var viewName = {
  'columnHeadersView': 'header',
  'rowsView': 'content',
  'footerView': 'footer',
  'headerPanel': 'headerPanel'
};
var VIEW_NAMES = ['columnHeadersView', 'rowsView', 'footerView', 'headerPanel'];

var ContextMenuController = _uiGrid_core.default.ViewController.inherit({
  init: function init() {
    this.createAction('onContextMenuPreparing');
  },
  getContextMenuItems: function getContextMenuItems(dxEvent) {
    if (!dxEvent) {
      return false;
    }

    var that = this;
    var $targetElement = (0, _renderer.default)(dxEvent.target);
    var $element;
    var $targetRowElement;
    var $targetCellElement;
    var menuItems;
    (0, _iterator.each)(VIEW_NAMES, function () {
      var view = that.getView(this);
      $element = view && view.element();

      if ($element && ($element.is($targetElement) || $element.find($targetElement).length)) {
        var _rowOptions$cells;

        $targetCellElement = $targetElement.closest('.dx-row > td, .dx-row > tr');
        $targetRowElement = $targetCellElement.parent();
        var rowIndex = view.getRowIndex($targetRowElement);
        var columnIndex = $targetCellElement[0] && $targetCellElement[0].cellIndex;
        var rowOptions = $targetRowElement.data('options');
        var options = {
          event: dxEvent,
          targetElement: (0, _element.getPublicElement)($targetElement),
          target: viewName[this],
          rowIndex: rowIndex,
          row: view._getRows()[rowIndex],
          columnIndex: columnIndex,
          column: rowOptions === null || rowOptions === void 0 ? void 0 : (_rowOptions$cells = rowOptions.cells) === null || _rowOptions$cells === void 0 ? void 0 : _rowOptions$cells[columnIndex].column
        };
        options.items = view.getContextMenuItems && view.getContextMenuItems(options);
        that.executeAction('onContextMenuPreparing', options);

        that._contextMenuPrepared(options);

        menuItems = options.items;

        if (menuItems) {
          return false;
        }
      }
    });
    return menuItems;
  },
  _contextMenuPrepared: _common.noop
});

var ContextMenuView = _uiGrid_core.default.View.inherit({
  _renderCore: function _renderCore() {
    var that = this;
    var $element = that.element().addClass(CONTEXT_MENU);
    this.setAria('role', 'presentation', $element);

    this._createComponent($element, _context_menu.default, {
      onPositioning: function onPositioning(actionArgs) {
        var event = actionArgs.event;
        var contextMenuInstance = actionArgs.component;
        var items = that.getController('contextMenu').getContextMenuItems(event);

        if (items) {
          contextMenuInstance.option('items', items);
          event.stopPropagation();
        } else {
          actionArgs.cancel = true;
        }
      },
      onItemClick: function onItemClick(params) {
        params.itemData.onItemClick && params.itemData.onItemClick(params);
      },
      cssClass: that.getWidgetContainerClass(),
      target: that.component.$element()
    });
  }
});

var contextMenuModule = {
  defaultOptions: function defaultOptions() {
    return {
      onContextMenuPreparing: null
    };
  },
  controllers: {
    contextMenu: ContextMenuController
  },
  views: {
    contextMenuView: ContextMenuView
  }
};
exports.contextMenuModule = contextMenuModule;