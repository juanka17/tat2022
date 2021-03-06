"use strict";

var _uiData_grid = _interopRequireDefault(require("./ui.data_grid.core"));

var _uiData_grid2 = _interopRequireDefault(require("./ui.data_grid.data_source_adapter"));

var _uiGrid_core = require("../grid_core/ui.grid_core.virtual_scrolling");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_uiData_grid.default.registerModule('virtualScrolling', _uiGrid_core.virtualScrollingModule);

_uiData_grid2.default.extend(_uiGrid_core.virtualScrollingModule.extenders.dataSourceAdapter);