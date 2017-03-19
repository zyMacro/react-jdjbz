import React, {Component} from 'react' 
import ReactDOM from 'react-dom'
import {Menu,Icon,Table,Button} from 'antd'
// import './nba.css'

const SubMenu=Menu.SubMenu;
const MenuItem=Menu.Item;

const columns=[
{
  title:'LAL',
  dataIndex:'Kobe',
},{
  title:'SPURS',
  dataIndex:'Tim Duncan',
}];
const products=[2,3];
class Nba extends React.Component{
 constructor(){
    super();

    this.state = {
      name:'Kobe Bryant'
    };
    this.handleName=this.handleName.bind(this)
  }
  handleName(event){
    this.setState({name: event.target.value})
  }

  
  render(){
    return (
    <div>
          <Menu mode="inline" theme="dark" style={{width: '240px'}}>
              <SubMenu key="user" title={<span><Icon type="mail"/><span>用户管理</span></span>}>
                <MenuItem key="user-list">            
                </MenuItem>
                <MenuItem key="user-add">
                </MenuItem>
              </SubMenu>
              <SubMenu key="book" title={<span><Icon type="book"/><span>图书管理</span></span>}>
                <MenuItem key="book-list">
                </MenuItem>
                <MenuItem key="book-add">                  
                </MenuItem>
              </SubMenu>
            </Menu>


        <p>Who is your favorite NBA player?</p>
      <input type='text'  onChange={this.handleName}/>
      <p>{this.state.name+' is MVP'}</p>
        
    </div>)
  }
}
// ReactDOM.render(<Nba />,document.getElementById('app'))
export default Nba