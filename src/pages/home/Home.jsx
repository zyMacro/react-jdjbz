import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import {Menu,Icon,Table,Button} from 'antd'
import Ajax from './ajax.jsx'
import Greeting from './greeting.jsx'
import DataTable from './dataTable.jsx'



class Home extends Component {
  render(){
    return <div>
    <Greeting></Greeting>
    <DataTable></DataTable>
    </div>
  }
}
export default Home;
