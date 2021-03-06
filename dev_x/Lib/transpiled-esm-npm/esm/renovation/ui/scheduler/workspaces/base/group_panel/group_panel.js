import _objectWithoutPropertiesLoose from "@babel/runtime/helpers/esm/objectWithoutPropertiesLoose";
import _extends from "@babel/runtime/helpers/esm/extends";
var _excluded = ["className", "elementRef", "groupByDate", "groupOrientation", "groupPanelData", "groups", "height", "resourceCellTemplate"];
import { createComponentVNode, normalizeProps } from "inferno";
import { InfernoWrapperComponent } from "@devextreme/runtime/inferno";
import { isVerticalGroupingApplied } from "../../utils";
import { GroupPanelBaseProps } from "./group_panel_props";
import { GroupPanelVerticalLayout } from "./vertical/layout";
import { GroupPanelHorizontalLayout } from "./horizontal/layout";
import { VERTICAL_GROUP_ORIENTATION } from "../../../consts";
export var viewFunction = _ref => {
  var {
    layout: Layout,
    props: {
      className,
      elementRef,
      groupPanelData,
      height,
      resourceCellTemplate
    },
    restAttributes
  } = _ref;
  return createComponentVNode(2, Layout, {
    "height": height,
    "resourceCellTemplate": resourceCellTemplate,
    "className": className,
    "groupPanelData": groupPanelData,
    "elementRef": elementRef,
    "styles": restAttributes.style
  });
};
export var GroupPanelProps = Object.create(Object.prototype, _extends(Object.getOwnPropertyDescriptors(GroupPanelBaseProps), Object.getOwnPropertyDescriptors({
  get groups() {
    return [];
  },

  groupOrientation: VERTICAL_GROUP_ORIENTATION
})));
import { createReRenderEffect } from "@devextreme/runtime/inferno";

var getTemplate = TemplateProp => TemplateProp && (TemplateProp.defaultProps ? props => normalizeProps(createComponentVNode(2, TemplateProp, _extends({}, props))) : TemplateProp);

export class GroupPanel extends InfernoWrapperComponent {
  constructor(props) {
    super(props);
    this.state = {};
  }

  createEffects() {
    return [createReRenderEffect()];
  }

  get layout() {
    var {
      groupOrientation,
      groups
    } = this.props;
    return isVerticalGroupingApplied(groups, groupOrientation) ? GroupPanelVerticalLayout : GroupPanelHorizontalLayout;
  }

  get restAttributes() {
    var _this$props = this.props,
        restProps = _objectWithoutPropertiesLoose(_this$props, _excluded);

    return restProps;
  }

  render() {
    var props = this.props;
    return viewFunction({
      props: _extends({}, props, {
        resourceCellTemplate: getTemplate(props.resourceCellTemplate)
      }),
      layout: this.layout,
      restAttributes: this.restAttributes
    });
  }

}
GroupPanel.defaultProps = GroupPanelProps;