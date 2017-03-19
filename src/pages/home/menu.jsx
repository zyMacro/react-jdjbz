import React,{Component} from 'React'
import ReactDOM from 'react-dom'
import {Menu,Icon,Button} from 'antd'
import {
  // BrowserRouter as Router,
  HashRouter as Router,
  Route,
  Link
} from 'react-router-dom'

import Run from "../sport/run.jsx"
import Walk from "../sport/walk.jsx"
import Rank from "../sport/rank.jsx"

const SubMenu=Menu.SubMenu;
const MenuItem=Menu.Item;

class MenuList extends React.Component{
	render(){
		return <Router>
		<div>
		<Menu mode='horizontal' theme="dark" style={{width: '100%'}}>
			<SubMenu title={<span><Icon type="appstore"/><span>首页</span></span>}>
				<MenuItem>
				</MenuItem>
			</SubMenu>
			<SubMenu title={<span><Icon type="appstore"/><span>运动</span></span>}>
				<MenuItem><Link to="../sport/rank.jsx">排名</Link></MenuItem>
				<MenuItem><Link to="../sport/walk.jsx">健步</Link></MenuItem>
				<MenuItem><Link to="../sport/run.jsx">跑步</Link></MenuItem>
			</SubMenu>
			<SubMenu title={<span><Icon type="appstore"/><span>饮食</span></span>}>
				<MenuItem>
				</MenuItem>
			</SubMenu>
			<SubMenu title={<span><Icon type="appstore"/><span>健康</span></span>}>
				<MenuItem>
				</MenuItem>
			</SubMenu>
		</Menu>
	<Route exact path="../sport/rank.jsx" component={Rank}/>
	<Route exact path="../sport/walk.jsx" component={Walk}/>
	<Route exact path="../sport/run.jsx" component={Run}/>
	</div>
	</Router>
	}
}
export default MenuList