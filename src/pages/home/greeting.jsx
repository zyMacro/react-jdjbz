import React,{Component} from 'React'
import ReactDOM from 'react-dom'
import {Row,Col} from 'antd'

class Greeting extends React.Component{
	request(){$.post('../public/account.php',function(data,textStatus){
	   $('#time')[0].innerText=data.time+' '+data.name;
	         },'json')}
	componentDidMount(){
		this.request();
	}
	render(){
		return <div>
		<Row>
        <p id='time'></p>
		</Row>
		</div>
	}
}
export default Greeting