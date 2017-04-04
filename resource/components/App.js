import React, { Component, PropTypes } from 'react';
import { Tabs, Tab } from 'material-ui/Tabs';
import { Route } from 'react-router-dom';
import Home from './Home';

class AppComponent extends Component {

  static contextTypes = {
    router: PropTypes.object.isRequired,
    muiTheme: PropTypes.object.isRequired,
  };

  handleChange = (value) => {
    const { router: { history: { replace } } } = this.context;
    replace(value);
  }

  getPathname() {
    const { router: { route: { location: { pathname } } } } = this.context;
    return pathname;
  }

  render() {
    console.log(this.context.muiTheme);
    return (
      <div
        style={{
          paddingTop: 48,
        }}
      >
        <Tabs
          value={this.getPathname()}
          onChange={this.handleChange}
          style={{
            position: 'fixed',
            width: '100%',
            top: 0,
          }}
        >
          <Tab label="动态信息" value="/" />
          <Tab label="动态管理" value="/feeds" />
          <Tab label="评论管理" value="/comments" />
        </Tabs>

        <Route exact path="/" component={Home} />

      </div>
    );
  }

}

export default AppComponent;
