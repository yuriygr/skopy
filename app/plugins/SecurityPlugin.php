<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

use Phalcon\Acl;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * ACL
 */
class SecurityPlugin extends Plugin
{
	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		// Check whether the "auth" variable exists in session to define the active role
		$auth = $this->auth->isLogin();

		if ($auth) {
			$role = 'Users';
		} else {
			$role = 'Guests';
		}

		// Take the active controller/action from the dispatcher
		$controller = $dispatcher->getControllerName();
		$action     = $dispatcher->getActionName();

		// Obtain the ACL list
		$acl = $this->getAcl();

		// Check if the Role have access to the controller (resource)
		$allowed = $this->isAllowed($role, $controller, $action);

		if ($allowed != Acl::ALLOW) {
			// If he doesn't have access forward him to the 403 page
			$dispatcher->forward([
				'controller' => 'pages',
				'action' => 'show403'
			]);

			// Returning "false" we tell to the dispatcher to stop the current operation
			return false;
		}
	}

	/**
	 * Returns an existing or new access control list
	 *
	 * @return AclList
	 */
	public function getAcl()
	{
		if (!isset($this->persistent->acl)) {
			$acl = new AclList();
			$acl->setDefaultAction(Acl::DENY);
			// Register roles
			$roles = [
				'Guests' => new Role(
					'Guests',
					'Anyone browsing the site who is not signed in is considered to be a "Guest".'
				),
				'Users'  => new Role(
					'Users',
					'Member privileges, granted after sign in.'
				)
			];
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			// Public area resources
			$publicResources = [
				'notes' 		=> ['list', 'show'],
				'pages' 		=> ['show', 'show403', 'show404', 'vika'],
				'users' 		=> ['list', 'show', 'login', 'logout']
			];
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			// Private area resources
			$privateResources = [
				'notes' 		=> ['create', 'edit', 'delete'],
				'pages' 		=> ['create', 'edit', 'delete'],
				'users' 		=> ['create', 'edit', 'delete']
			];
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			// Grant access to public areas to both users and guests
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					$acl->allow($role->getName(), $resource, $actions);
				}
			}

			// Grant access to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				$acl->allow('Users', $resource, $actions);
			}

			// The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}
		return $this->persistent->acl;
	}

	/**
	 * Checks if the current group is allowed to access a resource.
	 *
	 * @param string $group
	 * @param string $controller
	 * @param string $action
	 *
	 * @return bool
	 */
	public function isAllowed($group, $controller, $action)
	{
		return $this->getAcl()->isAllowed($group, $controller, $action);
	}
}