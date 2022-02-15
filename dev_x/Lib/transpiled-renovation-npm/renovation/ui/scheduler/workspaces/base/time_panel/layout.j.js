"use strict";

exports.default = void 0;

var _component_registrator = _interopRequireDefault(require("../../../../../../core/component_registrator"));

var _time_panel = require("../../../../../component_wrapper/scheduler/time_panel");

var _layout = require("./layout");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

function _inheritsLoose(subClass, superClass) { subClass.prototype = Object.create(superClass.prototype); subClass.prototype.constructor = subClass; _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

var TimePanelTableLayout = /*#__PURE__*/function (_TimePanel) {
  _inheritsLoose(TimePanelTableLayout, _TimePanel);

  function TimePanelTableLayout() {
    return _TimePanel.apply(this, arguments) || this;
  }

  _createClass(TimePanelTableLayout, [{
    key: "_propsInfo",
    get: function get() {
      return {
        twoWay: [],
        allowNull: [],
        elements: [],
        templates: ["timeCellTemplate"],
        props: ["groupOrientation", "timePanelData", "timeCellTemplate"]
      };
    }
  }, {
    key: "_viewComponent",
    get: function get() {
      return _layout.TimePanelTableLayout;
    }
  }]);

  return TimePanelTableLayout;
}(_time_panel.TimePanel);

exports.default = TimePanelTableLayout;
(0, _component_registrator.default)("dxTimePanelTableLayout", TimePanelTableLayout);
module.exports = exports.default;
module.exports.default = exports.default;