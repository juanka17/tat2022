"use strict";

exports.isViewDataProviderConfigValid = exports.createTimeZoneCalculator = exports.createDataAccessors = void 0;

var _utils = require("../../../ui/scheduler/utils");

var _utils2 = require("./timeZoneCalculator/utils");

var _utils3 = _interopRequireDefault(require("../../../ui/scheduler/utils.timeZone"));

var _utils4 = require("../../../ui/scheduler/resources/utils");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

var createDataAccessors = function createDataAccessors(props) {
  var forceIsoDateParsing = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

  var dataAccessors = _utils.utils.dataAccessors.create({
    startDate: props.startDateExpr,
    endDate: props.endDateExpr,
    startDateTimeZone: props.startDateTimeZoneExpr,
    endDateTimeZone: props.endDateTimeZoneExpr,
    allDay: props.allDayExpr,
    text: props.textExpr,
    description: props.descriptionExpr,
    recurrenceRule: props.recurrenceRuleExpr,
    recurrenceException: props.recurrenceExceptionExpr
  }, null, forceIsoDateParsing, props.dateSerializationFormat);

  dataAccessors.resources = (0, _utils4.createExpressions)(props.resources);
  return dataAccessors;
};

exports.createDataAccessors = createDataAccessors;

var createTimeZoneCalculator = function createTimeZoneCalculator(currentTimeZone) {
  return new _utils2.TimeZoneCalculator({
    getClientOffset: function getClientOffset(date) {
      return _utils3.default.getClientTimezoneOffset(date);
    },
    getCommonOffset: function getCommonOffset(date) {
      return _utils3.default.calculateTimezoneByValue(currentTimeZone, date);
    },
    getAppointmentOffset: function getAppointmentOffset(date, appointmentTimezone) {
      return _utils3.default.calculateTimezoneByValue(appointmentTimezone, date);
    }
  });
};

exports.createTimeZoneCalculator = createTimeZoneCalculator;

var isViewDataProviderConfigValid = function isViewDataProviderConfigValid(viewDataProviderConfig, currentViewOptions) {
  if (!viewDataProviderConfig) {
    return false;
  }

  var result = true;
  Object.entries(viewDataProviderConfig).forEach(function (_ref) {
    var _ref2 = _slicedToArray(_ref, 2),
        key = _ref2[0],
        value = _ref2[1];

    if (value !== currentViewOptions[key]) {
      result = false;
    }
  });
  return result;
};

exports.isViewDataProviderConfigValid = isViewDataProviderConfigValid;