import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import {Menu,Icon,Button,Dropdown} from 'antd'
import {
  // BrowserRouter as Router,
  HashRouter as Router,
  Route,
  Link,
} from 'react-router-dom'
import RankAll from './rankAll.jsx'
import RankSchool from './rankSchool.jsx'
import RankHistory from './rankHistory.jsx'
import RankSchoolAverage from './rankSchoolAverage.jsx'
import RankAverage from './rankAverage.jsx'

const MenuItem=Menu.Item;
class RankMenu extends React.Component{
	render(){
		const menu1=(
			<Menu>
			<MenuItem>
			<Link to='/rankAll'>实时排名</Link>
			</MenuItem>
			<MenuItem>
			<Link to='/rankAll'>平均步数排名</Link>
			</MenuItem>
			</Menu>
	    )
    	const menu2=(
			<Menu>
			<MenuItem>
			<Link to='/rankSchool'>实时排名</Link>
			</MenuItem>
			<MenuItem>
			<Link to='/rankSchoolAverage'>平均步数排名</Link>
			</MenuItem>
			</Menu>
	    )
		return <Router> 
		<div><h2>排名</h2>
			<Dropdown overlay={menu1}>
			<span className="ant-dropdown-link">全校<Icon type='down' /></span>
			</Dropdown>
			<Dropdown overlay={menu2}>
		   <span className="ant-dropdown-link">单位<Icon type='down' /></span>
			</Dropdown>		
		   <a className="ant-dropdown-link" href='#/rankHistory'>历史<Icon type='down' /></a>
		<Route path='/rankAll' Component='RankAll' />
		<Route path='/rankSchool' Component='RankSchool' />
		<Route path='/rankHistory' Component='RankHistory' />
		<Route path='/rankSchool' Component='RankSchool' />
		<Route path='./rankSchoolAverage' Component='RankSchoolAverage' />
		</div>
		</Router>
	}
}
export default RankMenu;
// <Dropdown overlay={menu1}>
// <a className="ant-dropdown-link" href="#/rankAll">全校<Icon type='down' /></a>
// </Dropdown>
// <Dropdown overlay={menu2}>
// <a className="ant-dropdown-link" href="#/rankSchool">单位<Icon type='down' /></a>
// </Dropdown>
// <Dropdown overlay={menu3}>
// <a className="ant-dropdown-link" href="#/rankHistory">历史<Icon type='down' /></a>
// </Dropdown>

// <Link to='/rankAll'>全校</Link><Icon type='down' />