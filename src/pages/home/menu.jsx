import React,{Component} from 'React'
import ReactDOM from 'react-dom'
import {Menu,Icon,Button} from 'antd'
import {
  // BrowserRouter as Router,
  HashRouter as Router,
  Route,
  Link,
} from 'react-router-dom'
// import {Switch} from 'react-router'

import Run from "../sport/run.jsx"
import Walk from "../sport/walk.jsx"
import Rank from "../sport/rank.jsx"
import Sport from '../sport/sport.jsx'
import Home from '../home/home.jsx'

const SubMenu=Menu.SubMenu;
const MenuItem=Menu.Item;

class MenuList extends React.Component{
	render(){
		return <Router>
		<div>
		<Menu mode='horizontal' theme="dark" style={{width: '100%'}}>
			<SubMenu title={<span><Icon type="appstore"/><span><Link to='/'>首页</Link></span></span>}>
			</SubMenu>
			<SubMenu title={<span><Icon type="appstore"/><span><Link to='/sport'>运动</Link></span></span>}>
				<MenuItem><Link to="/rank">排名</Link></MenuItem>
				<MenuItem><Link to="/walk">健步</Link></MenuItem>
				<MenuItem><Link to="/run">跑步</Link></MenuItem>
			</SubMenu>
			<SubMenu title={<span><Icon type="appstore"/><span>饮食</span></span>}>
			</SubMenu>
			<SubMenu title={<span><Icon type="appstore"/><span>健康</span></span>}>
			</SubMenu>
		</Menu>
		
	    <Route exact path="/" component={Home}/>
		<Route  path='/sport' component={Sport}/>
		<Route  path="/rank" component={Rank}/>
		<Route  path="/walk" component={Walk}/>
		<Route  path="/run" component={Run}/>
		</div>
	    </Router>
	
	
	}
}
export default MenuList