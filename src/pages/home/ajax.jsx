import React,{Component} from 'React'

class Ajax extends React.Component{
	 request(){$.post('../public/test_ajax.php',function(data,textStatus){
	   $('#haha')[0].innerText=data.a;
	         },'json')}
	componentDidMount(){
		this.request();
	}
	render(){
		return <div>
		<p id='haha'>123
		</p>	
		</div>  
	  }
	
}
export default Ajax