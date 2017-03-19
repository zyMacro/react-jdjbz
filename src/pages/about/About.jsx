import React, {Component} from 'react'
import './about.scss'

class About extends Component {
  constructor(){
    super();
    this.state = {
      name:'hongyuanzhang'
    }
  }
  time(){
    this.setState({
        name:''
    })
  }
  render(){
    return <section id='about'>
        我是{this.state.name}
        <a onClick={this.time.bind(this)}>clear</a>
        <input type="text" onChange={e=>this.setState({name:e.target.value})} value={this.state.name}/>
    </section>
  }
}


export default About;
