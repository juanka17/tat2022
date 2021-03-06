"use strict";

exports.getTimeZoneCalculator = exports.getModelProvider = exports.generateKey = exports.disposeFactoryInstances = exports.createModelProvider = exports.createInstance = exports.createFactoryInstances = void 0;

var _type = require("../../core/utils/type");

var _modelProvider = require("./modelProvider");

var _utils = require("../../renovation/ui/scheduler/timeZoneCalculator/utils");

var _utils2 = _interopRequireDefault(require("./utils.timeZone"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Names = {
  timeZoneCalculator: 'timeZoneCalculator',
  appointmentDataProvider: 'appointmentDataProvider',
  model: 'model',
  modelProvider: 'modelProvider'
};
var factoryInstances = {};
var tailIndex = -1;

var generateKey = function generateKey(key) {
  return (0, _type.isDefined)(key) ? key : ++tailIndex;
};

exports.generateKey = generateKey;

var createFactoryInstances = function createFactoryInstances(options) {
  var key = generateKey(options.key);
  createModelProvider(key, options.model);
  createTimeZoneCalculator(key, options.timeZone);
  return key;
};

exports.createFactoryInstances = createFactoryInstances;

var createInstance = function createInstance(name, key, callback) {
  if (!(0, _type.isDefined)(factoryInstances[name])) {
    factoryInstances[name] = {};
  }

  var result = callback();
  factoryInstances[name][key] = result;
  return result;
};

exports.createInstance = createInstance;

var getInstance = function getInstance(name, key) {
  return factoryInstances[name] ? factoryInstances[name][key] : undefined;
};

var removeInstance = function removeInstance(name, key) {
  if (getInstance(name, key)) {
    factoryInstances[name] = null;
  }
};

var createTimeZoneCalculator = function createTimeZoneCalculator(key, currentTimeZone) {
  return createInstance(Names.timeZoneCalculator, key, function () {
    return new _utils.TimeZoneCalculator({
      getClientOffset: function getClientOffset(date) {
        return _utils2.default.getClientTimezoneOffset(date);
      },
      getCommonOffset: function getCommonOffset(date, timeZone) {
        return _utils2.default.calculateTimezoneByValue(timeZone || currentTimeZone, date);
      },
      getAppointmentOffset: function getAppointmentOffset(date, appointmentTimezone) {
        return _utils2.default.calculateTimezoneByValue(appointmentTimezone, date);
      }
    });
  });
};

var createModelProvider = function createModelProvider(key, model) {
  return createInstance(Names.modelProvider, key, function () {
    var modelProvider = getInstance(Names.modelProvider, key);
    return (0, _type.isDefined)(modelProvider) ? modelProvider : new _modelProvider.ModelProvider(model);
  });
};

exports.createModelProvider = createModelProvider;

var disposeFactoryInstances = function disposeFactoryInstances(key) {
  Object.getOwnPropertyNames(Names).forEach(function (name) {
    removeInstance(name, key);
  });
};

exports.disposeFactoryInstances = disposeFactoryInstances;

var getTimeZoneCalculator = function getTimeZoneCalculator(key) {
  return getInstance(Names.timeZoneCalculator, key);
};

exports.getTimeZoneCalculator = getTimeZoneCalculator;

var getModelProvider = function getModelProvider(key) {
  return getInstance(Names.modelProvider, key);
};

exports.getModelProvider = getModelProvider;