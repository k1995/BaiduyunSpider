import React from 'react';
import { Grid } from '@material-ui/core';
import MUIDataTable from "mui-datatables";
import { connect } from 'react-redux'
import PageTitle from '../../components/PageTitle';
import { fetchUsers } from './UserState'


class Users extends React.Component {

  state = {
    page: 1,
    pageSize: 10,
  };

  refetch = () => {
    this.props.dispatch(fetchUsers(this.state.page + 1, this.state.pageSize));
  };

  componentDidMount() {
    this.refetch();
    this.timer = setInterval(this.refetch, 5000);
  }

  componentWillUnmount() {
    clearInterval(this.timer);
  }

  render() {
  	const { users, total } = this.props['users'];
  	const data = users.map(user => [user['uname'], user['avatar_url'], user['uk'], user['last_updated']]);
    const columns = [
    	"昵称",
			{
				name: "头像",
        options: {
          customBodyRender: (value, tableMeta, updateValue) => <img alt="头像" width={30} src={value}/>,
        }
			},
			"UK",
			"更新时间"
		];

    const options = {
      count: total,
      serverSide: true,
      page: this.state.page,
      rowsPerPage: this.state.pageSize,
      onTableChange: (action, tableState) => {
        this.setState({
          page: tableState.page,
          pageSize: tableState.rowsPerPage
        });
        setTimeout(() => this.refetch(), 0);
      }
    };

  	return (
      <React.Fragment>
        <PageTitle title="分享者" />
        <Grid container spacing={32}>
          <Grid item xs={12}>
            <MUIDataTable
              title="分享者列表"
              data={data}
              columns={columns}
              options={options}
            />
          </Grid>
        </Grid>
      </React.Fragment>
    );
	}
}


const mapStateToProps = state => state;
export default connect(mapStateToProps)(Users)