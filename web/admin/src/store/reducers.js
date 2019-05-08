import { combineReducers } from 'redux';

import layout from '../components/Layout/LayoutState';
import login from '../pages/login/LoginState';
import users from '../pages/users/UserState';
import files from '../pages/files/FileState';

export default combineReducers({
  layout,
  login,
  users,
	files
});