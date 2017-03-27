import React,{Component} from 'React'
import ReactDOM from 'react-dom'
import {Row,Col,Tabs,Table} from 'antd'

class Average extends React.Component{
	constructor(){
		super();
		this.state:{
			data:{}
		}	
	}
	request(){$.post('../public/week_data.php',data=>{
		this.setState({
			data

	});
	componentDidMount(){
		this.request();
	}
	render(){
		
	}

}
export default Average;