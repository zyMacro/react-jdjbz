"use strict";
//基本组件
import React, {Component} from 'react'
import {render} from 'react-dom'


import {
  // BrowserRouter as Router,
  HashRouter as Router,
  Route,
  Link
} from 'react-router-dom'

import Home from '../pages/home'
import Sport from '../pages/sport'
import Diet from '../pages/diet'
import Health from '../pages/health'
import Log from '../pages/log'
import MenuList from '../pages/home/menu.jsx'



const BasicExample = () => (
<MenuList></MenuList>
)
render(<BasicExample/>, document.getElementById('app'));
   // <Route exact path='/sport' component={Sport} />