<?php
const STR_ROOT = '/roemedia/beerfest/';
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
//require '_packages/system/Beerfest.php';
require '_system/Crypt.php';
require '_config/config.inc.php';
require '_system/Auth.php';
require '_system/Request.php';
require '_system/functions.php';
require 'db/Database.php';
require 'db/DBTable_base.php';
require 'db/DBTable.php';
require 'db/GenericObject.php';

// Controllers
require '_system/Element.php';
require '_system/FormController.php';
require '_system/FormElement.php';
require '_system/FormElementHidden.php';
require '_system/FormElementText.php';
require '_system/FormElementPassword.php';
require '_system/FormElementTextArea.php';
require '_system/FormElementSelect.php';
require '_system/FormElementButton.php';
require '_system/FormElementRange.php';
require '_system/FormElementRangeSlider.php';
require '_system/FormElementFile.php';
require '_system/ListInterface.php';
require '_system/ListController.php';
require '_system/HtmlList.php';
require '_system/ListColumn.php';
require '_system/ListRow.php';
require '_system/Button.php';
require '_system/Navigation.php';
require '_system/NavigationElement.php';
require '_system/Menu.php';
require '_lang/no.php';

require '_modules/user/UserDB.php';
require '_modules/user/User.php';
require '_modules/user/Users.php';
require '_modules/user/List.php';
require '_modules/fest/FestDB.php';
require '_modules/fest/Fest.php';
require '_modules/fest/Fests.php';
require '_modules/fest/Results.php';
require '_modules/fest/Item.php';
require '_modules/fest/Items.php';
require '_modules/fest/ItemDB.php';
require '_modules/fest/ItemVoteDB.php';
require '_modules/fest/ItemVote.php';
require '_modules/fest/ItemVoteForm.php';
require '_modules/fest/ItemVotes.php';
require '_modules/fest/ParticipantDB.php';
require '_modules/fest/Participants.php';
require '_modules/fest/Participant.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

$blnAjax = $app->request->isXhr();
$app->view()->setTemplatesDirectory('_system/templates');

if(!$blnAjax)
{
    $app->response->write($app->render('header.html'));
    $objAuth = new \Beerfest\Core\Auth();
    if($objAuth->isAuthenticated() === false)
    {
        require '_modules/user/LoginForm.php';
        $objLogin = new \Beerfest\User\LoginForm();
        $app->render('login.php', array('data' => $objLogin->getHtml()));
        exit;
    }
    else
    {
        $app->response->write($app->render('content.php'));
    }
}

// Welcome
$app->get('/', function () {
});


$app->get('/init', function () use ($app) {
    $objMenu = new Beerfest\Menu\Menu();
    $aryInit = array();
    $aryInit['menu'] = $objMenu->getItems();
    $aryInit['panel'] = $objMenu->getPanelItems();
    $app->response->write(json_encode($aryInit));
});


/**
 * User
 */
$app->get('/logout', function() use($app) {
    $objAuth = new \Beerfest\Core\Auth();
    $objAuth->logOut();
    $app->redirect(STR_ROOT);
});

$app->get('/user/list', function() use($app) {
    $objList = new \Beerfest\User\UserList();

    if($app->request->isXhr())
    {
        $app->stop();
    }
    else
    {
        $app->response->write($objList->getListHtml());
    }
});

// View
$app->get('/user:strUserId', function($strUserId) use($app) {
    $strUserId = str_replace(':', '', $strUserId);
    require '_modules/User/Form.php';
    require '_modules/User/Details.php';
    $objUser = new \Beerfest\User\User($strUserId);
    $objDetails = new \Beerfest\User\Details($objUser);
    $strBody = $objDetails->getHtml();
    $app->response->setBody($strBody);
});

$app->get('/user/add', function() use($app) {
    require '_modules/User/Form.php';
    $objUser = new \Beerfest\User\User();
    $objForm = new \Beerfest\User\Form($objUser);
    $strBody = $objForm->getHtml();
    $app->response->setBody($strBody);
});

// Edit
$app->get('/user:strUserId/edit', function($strUserId) use($app) {
    $strUserId = str_replace(':', '', $strUserId);
    require '_modules/User/Form.php';
    $objUser = new \Beerfest\User\User($strUserId);
    $objForm = new \Beerfest\User\Form($objUser);
    $strBody = $objForm->getHtml();
    $app->response->setBody($strBody);
});


$app->post('/user/login', function() use ($app)
{
    $aryPost = $app->request->post('data');
    if($app->request->isXhr())
    {
        $aryPost = ajax_decode($aryPost);
    }
    $objAuth = new \Beerfest\Core\Auth;
    $blnValid = false;
    if(isset($aryPost['username']) && isset($aryPost['password']))
    {
        $blnValid = $objAuth->checkLogin($aryPost['username'], $aryPost['password']);
    }

    if($blnValid)
    {
        $aryResult['code'] = 200;
        $aryResult['data'] = STR_ROOT;
        $app->response->header(200);
    }
    else
    {
        $aryResult = array(
            'code' => 401,
            'data' => _LOGIN_ERROR
        );
    }

    if($app->request->isXhr())
    {
        $app->response->write(json_encode($aryResult));
    }
    else
    {
        $app->redirect('', 200);
    }
});

$app->post('/user:strUserId', function($strUserId) use($app) {
    require '_modules/user/Form.php';
    $strUserId = str_replace(':', '', $strUserId);
    $objUser = new \Beerfest\User\User($strUserId);
    $aryPost = $app->request->post('data');
    if($app->request->isXhr())
    {
        $aryPost = ajax_decode($aryPost);
    }
    $objForm = new \Beerfest\User\Form($objUser);
    if($objForm->validate($aryPost) == true)
    {
        $arySave = $objForm->getPostData();
        foreach($arySave as $strKey => $strValue)
        {
            $objUser->set($strKey, $strValue);
        }
        $objUser->save();

        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 200, 'data' => $objForm->getReferer())));
        }
        else
        {
            $app->redirect($objForm->getReferer());
        }
    }
    else
    {
        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 500, 'data' => $objForm->getErrors())));
        }
        else
        {
            $objForm->setDefaults($aryPost);
            echo $objForm->getHtml();
        }
    }
});

// Delete user
$app->delete('/user/delete', function () use($app) {
        $strId = $app->request->post('id');
        $objUser = new \Beerfest\User\User($strId);
        $objUser->delete();
        if($app->request->isXhr())
        {
            $app->response->header(200);
            $app->response->write(json_encode(array('code' => 200)));
        }
        else
        {
        }
    }
);

/** User finished */


/**
 * Fest handlers
 */

// View fest
$app->get('/fest:strFestId', function($strFestId) use($app) {
    require '_modules/fest/Details.php';
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    require '_modules/fest/Form.php';
    $objDetails = new \Beerfest\Fest\Details($objFest);
    $strBody = $objDetails->getHtml();
    $app->response->setBody($strBody);
});

// Edit fest
$app->get('/fest:strFestId/edit', function($strFestId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    require '_modules/fest/Form.php';
    $objForm = new \Beerfest\Fest\Form($objFest);
    $strBody = $objForm->getHtml();
    $app->response->setBody($strBody);
});

// Add fest
$app->get('/fest/add', function() use($app) {
    require '_modules/fest/Form.php';
    $objFest = new \Beerfest\Fest\Fest();
    $objForm = new \Beerfest\Fest\Form($objFest);
    $strBody = $objForm->getHtml();
    $app->response->setBody($strBody);
});

// Save fest
$app->post('/fest:strFestId', function($strFestId) use ($app) {
    require '_modules/fest/Form.php';
    $aryPost = $app->request->post();
    $strFestId = str_replace(':', '', $strFestId);
    if($strFestId == 'add')
    {
        $objFest = new \Beerfest\Fest\Fest();
    }
    else
    {
        $objFest = new \Beerfest\Fest\Fest($strFestId);
    }

    if($app->request->isXhr())
    {
        $aryPost = ajax_decode($aryPost['data']);
    }
    $objForm = new \Beerfest\Fest\Form($objFest);
    if($objForm->validate($aryPost) == true)
    {
        $arySave = $objForm->getPostData();
        foreach($arySave as $strKey => $strValue)
        {
            $objFest->set($strKey, $strValue);
        }
        $objFest->save();

        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 200, 'data' => $objForm->getReferer())));
        }
        else
        {
            $app->redirect($objForm->getReferer());
        }
    }
    else
    {
        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 500, 'data' => $objForm->getErrors())));
        }
        else
        {
            $objForm->setDefaults($aryPost);
            echo $objForm->getHtml();
        }
    }
});

// Start fest
$app->get('/fest:strFestId/start', function ($strFestId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    $objFest->start();
    $app->redirect(STR_ROOT . 'fest:' . $strFestId);
});

// Fest results
$app->get('/fest:strFestId/result', function ($strFestId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    $strFestResult = $objFest->getResultAsHtml();
    $app->response->write($strFestResult);
});

// Fest list
$app->get('/fest/list', function() use($app) {
    if($app->request->isXhr())
    {
    }
    else
    {
        require '_modules/fest/List.php';
        $objList = new \Beerfest\Fest\FestList();

        $app->response->write($objList->getListHtml());
    }
});

// Fest items list
$app->get('/fest:strFestId/items', function($strFestId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    require '_modules/fest/ItemsList.php';
    $objList = new \Beerfest\Fest\Item\ItemsList($objFest);
    $strBody = $objList->getListHtml();
    $app->response->setBody($strBody);
});

// Fest item edit
$app->get('/fest:strFestId/item/add', function($strFestId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    $objItem = new \Beerfest\Fest\Item\Item();
    require '_modules/fest/ItemForm.php';
    $objForm = new \Beerfest\Fest\Item\Form($objItem);
    $objForm->setDefaults(array(\Beerfest\Fest\Item\ItemDB::COL_FEST_ID => $objFest->getId()));
    $strBody = $objForm->getHtml();
    $app->response->setBody($strBody);
});

// Fest participants list
$app->get('/fest:strFestId/participants', function ($strFestId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new Beerfest\Fest\Fest($strFestId);
    require '_modules/fest/ParticipantsList.php';
    $objList = new Beerfest\Fest\Participant\ParticipantsList($objFest);
    $strBody = $objList->getListHtml();
    $app->response->setBody($strBody);
});

// Next item
$app->get('/fest:strFestId/next:strItemId', function ($strFestId, $strItemId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $strItemId = str_replace(':', '', $strItemId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    $objItem = new \Beerfest\Fest\Item\Item($strItemId);
    if($objItem->getId())
    {
        $objFest->setCurrentItem($objItem);
    }
    $app->response->redirect(STR_ROOT . 'fest:' . $strFestId);
});

// Current item
$app->put('/fest:strFestId/current:strItemId', function ($strFestId, $strItemId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $strItemId = str_replace(':', '', $strItemId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    $objItem = new \Beerfest\Fest\Item\Item($strItemId);
    if($objItem->getId())
    {
        $objFest->setCurrentItem($objItem);
    }

    if($app->request->isXhr())
    {
        $app->response->write(json_encode(array('code' => 200)));
    }
});

// Edit item
$app->get('/item:strItemId/edit', function($strId) use($app) {
    $strId = str_replace(':', '', $strId);
    $objItem = new \Beerfest\Fest\Item\Item($strId);
    require '_modules/fest/ItemForm.php';
    $objForm = new \Beerfest\Fest\Item\Form($objItem);
    $strBody = $objForm->getHtml();
    $app->response->setBody($strBody);
});

// View item
$app->get('/item:strItemId', function($strId) use($app) {
    $strId = str_replace(':', '', $strId);
    $objItem = new \Beerfest\Fest\Item\Item($strId);
    require '_modules/fest/ItemDetails.php';
    $objDetails = new Beerfest\Fest\Item\Details($objItem);
    $app->response->write($objDetails->getAsHtml());
});

// Save item
$app->post('/item:strItemId', function($strId) use($app) {
    $strId = str_replace(':', '', $strId);
    $aryPost = $app->request->post();
    require '_modules/fest/ItemForm.php';
    if($strId == 'add')
    {
        $objItem = new \Beerfest\Fest\Item\Item();
    }
    else
    {
        $objItem = new \Beerfest\Fest\Item\Item($strId);
    }

    if($app->request->isXhr())
    {
        $aryPost = ajax_decode($aryPost['data']);
    }
    $objForm = new \Beerfest\Fest\Item\Form($objItem);
    if($objForm->validate($aryPost) == true)
    {
        $arySave = $objForm->getPostData();
        foreach($arySave as $strKey => $strValue)
        {
            $objItem->set($strKey, $strValue);
        }
        $objItem->save();

        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 200, 'data' => $objForm->getReferer())));
        }
        else
        {
            $app->redirect($objForm->getReferer());
        }
    }
    else
    {
        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 500, 'data' => $objForm->getErrors())));
        }
        else
        {
            $objForm->setDefaults($aryPost);
            echo $objForm->getHtml();
        }
    }
});


/**
 * Vote
 */

$app->post('/item:strId/vote', function($strId) use($app) {
    $strId = str_replace(':', '', $strId);
    $aryPost = $app->request->post('data');
    if($app->request->isXhr())
    {
        $aryPost = ajax_decode($aryPost);
    }
    $objItem = new \Beerfest\Fest\Item\Item($strId);
    $objForm = new \Beerfest\Fest\Item\Vote\Form($objItem);
    if($objForm->validate($aryPost) == true)
    {
        $objForm->saveVote();

        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 200, 'data' => $objForm->getReferer())));
        }
        else
        {
            $app->redirect($objForm->getReferer());
        }
    }
    else
    {
        if($app->request->isXhr())
        {
            $app->response->write(json_encode(array('code' => 500, 'data' => $objForm->getErrors())));
        }
        else
        {
            $objForm->setDefaults($aryPost);
            echo $objForm->getHtml();
        }
    }
});


/** /Vote */

$app->put('/fest:strId/toggle', function ($strId) use ($app) {
    $strId = str_replace(':', '', $strId);
    $objFest = new Beerfest\Fest\Fest($strId);
    $objFest->toggleActive();
    $app->response->write($objFest->get(Beerfest\Fest\FestDB::COL_ACTIVE));
});

$app->put('/participant:strId/toggle', function ($strId) use ($app) {
    $strId = str_replace(':', '', $strId);
    $objParticipant = new Beerfest\Fest\Participant\Participant($strId);
    $objParticipant->toggleActive();
    $app->response->write($objParticipant->get(Beerfest\Fest\Participant\ParticipantDB::COL_ACTIVE));
});

$app->post('/fest:strFestId/participant/add:strUserId', function($strFestId, $strUserId) use($app) {
    $strFestId = str_replace(':', '', $strFestId);
    $objFest = new \Beerfest\Fest\Fest($strFestId);
    $strUserId = str_replace(':', '', $strUserId);
    $objUser = new \Beerfest\User\User($strUserId);
    $objParticipant = new \Beerfest\Fest\Participant\Participant('new');
    $objParticipant->set(\Beerfest\Fest\Participant\ParticipantDB::COL_USERID, $objUser->getId());
    $objParticipant->set(\Beerfest\Fest\Participant\ParticipantDB::COL_FESTID, $objFest->getId());
    $objParticipant->set(\Beerfest\Fest\Participant\ParticipantDB::COL_ACTIVE, true);
    $objParticipant->save();

    if($app->request->isXhr())
    {
        $app->response->write(json_encode(array('code' => 200, 'crypt_id' => $objParticipant->getCryptId())));
    }
});

$app->delete('/fest:strFestId', function($strFestId) use ($app) {
    $objFest = new \Beerfest\fest\Fest(str_replace(':', '', $strFestId));
    $blnSuccess = $objFest->delete();

    if($app->request->isXhr())
    {
        if($blnSuccess === false)
        {
            $app->response->header(400);
        }
        else
        {
            $app->response->header(200);
        }
        $app->stop();
    }
});

$app->delete('/item:strItemId', function($strItemId) use($app) {
    $strItemId = str_replace(':', '', $strItemId);
    $objItem = new \Beerfest\Fest\Item\Item($strItemId);
    $blnSuccess = $objItem->delete();

    if($app->request->isXhr())
    {
        if($blnSuccess === false)
        {
            $app->response->header(400);
        }
        else
        {
            $app->response->header(200);
            $app->response->write(json_encode(array('code' => 200)));
        }
    }
});

if(!$blnAjax)
{
    //$app->response->write($app->render('system/templates/footer.html'));
}

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();