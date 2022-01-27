"use strict";

exports.default = void 0;

var _component_registrator = _interopRequireDefault(require("../../../../../../../core/component_registrator"));

var _date_table = require("../../../../../../component_wrapper/scheduler/date_table");

var _table = require("./table");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

function _inheritsLoose(subClass, superClass) { subClass.prototype = Object.create(superClass.prototype); subClass.prototype.constructor = subClass; _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

var AllDayTable = /*#__PURE__*/function (_DateTable) {
  _inheritsLoose(AllDayTable, _DateTable);

  function AllDayTable() {
    return _DateTable.apply(this, arguments) || this;
  }

  _createClass(AllDayTable, [{
    key: "_propsInfo",
    get: function get() {
      return {
        twoWay: [],
        allowNull: [],
        elements: [],
        templates: ["dataCellTemplate"],
        props: ["viewData", "groupOrientation", "leftVirtualCellWidth", "rightVirtualCellWidth", "topVirtualRowHeight", "bottomVirtualRowHeight", "addDateTableClass", "addVerticalSizesClassToRows", "width", "dataCellTemplate"]
      };
    }
  }, {
    key: "_viewComponent",
    get: function get() {
      return _table.AllDayTable;
    }
  }]);

  return AllDayTable;
}(_date_table.DateTable);

exports.default = AllDayTable;
(0, _component_registrator.default)("dxAllDayTable", AllDayTable);
module.exports = exports.default;
module.exports.default = exports.default;