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
import MenuList from '../pages/home/menu.jsx'



const BasicExample = () => (
  <Router>
    <div>
      <MenuList></MenuList>
    </div>
  </Router>
)

render(<BasicExample/>, document.getElementById('app'));
    // <ul>
      
      //  <li><Link to="/Home">Home</Link></li>
       // <li><Link to="/sport">Sport</Link></li>
        //<li><Link to="/diet">Diet</Link></li>
        //<li><Link to="/Health">Health</Link></li>
         // <Route exact path="/Home" component={Home}/>
      // <Route exact path="/sport" component={Sport}/>
      // <Route exact path="/diet" component={Diet}/>
      // <Route exact path='/health' component={Health} />
      //</ul>
 // <li><Link to="/">index</Link></li> 
 //    <Route exact path="/" component={Home}/>