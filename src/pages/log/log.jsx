import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import './log.css'

class Log extends React.Component{
	render(){
		return <div id='log'>
    <img src='../img/sjtu.png'></img>
    <img src='../img/jdjbz-logo.jpg' width='250px' ></img>
    <img src='../img/auth.png' width='500'></img>
    <a href='../public/xmauth.php'><img src="../img/xiaomi-login.png" width='500'></img></a>
    <a href='../public/jaccount-auth.php'><img src="../img/jaccount-login.png" width='500' ></img></a>
        {/*<Row type='flex' justify='center'><Col span={10}><img src='http://front.sjtu.edu.cn/jdjbz/img/jdjbz-s.jpg' /></Col></Row>
        <Row type='flex' justify='center'><Col span={12}><img src='../img/sjtu.png'></img></Col></Row>
        <Row type='flex' justify='center'><Col span={12}><img src='../img/jdjbz-logo.jpg'  ></img></Col></Row>
        <Row type='flex' justify='center'><Col span={12}><img src='../img/auth.png' ></img></Col></Row>
        <Row type='flex' justify='center'><Col span={12}><Link to='../public/xmauth.php'><img src="../img/xiaomi-login.png" ></img></Link></Col></Row>
        <Row type='flex' justify='center'><Col span={12}><Link to='../public/jaccount-auth.php'><img src="../img/jaccount-login.png" ></img></Link></Col></Row>*/}
		</div>
	}
}
export default Log