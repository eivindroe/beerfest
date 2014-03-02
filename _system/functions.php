<?php

/**
 * Get secure sql where clause
 *
 * @param string $strKey Key
 * @param string $strValue Value
 *
 * @since 16. February 2014, v. 1.00
 * @return string Where clause
 */
function sql_where($strKey, $strValue)
{
    return '`' . $strKey . '`="' . $strValue . '"';
}// sql_where


/**
 * Get secure sql where in clause
 *
 * @param string $strKey SQL column key
 * @param array $aryValues Values
 *
 * @since 22. February 2014, v. 100
 * @return string SQL where in clause
 */
function sql_where_in($strKey, $aryValues)
{
    $strSql = '';
    if(count($aryValues))
    {
        $strSql = '`' . $strKey . '` IN (' . implode(', ', $aryValues) . ')';
    }
    return $strSql;
}// sql_where_in


/**
 * Get secure sql where not in clause
 *
 * @param string $strKey SQL column key
 * @param array $aryValues Values
 *
 * @since 22. February 2014, v. 100
 * @return string SQL where not in clause
 */
function sql_where_not_in($strKey, $aryValues)
{
    $strSql = '';
    if(count($aryValues))
    {
        $strSql = '`' . $strKey . '` NOT IN (' . implode(', ', $aryValues) . ')';
    }
    return $strSql;
}// sql_where_not_in


/**
 * Get crypt sql
 *
 * @param string $strKey SQL column key
 * @param string $strValue Value
 *
 * @since 22. February 2014, v. 1.00
 * @return string Crypt sql
 */
function crypt_sql($strKey, $strValue)
{
    return 'md5(`'. $strKey . '`)="' . $strValue . '"';
}// crypt_sql


/**
 * Get user id
 *
 * @since 22. February 2014, v. 1.00
 * @return integer User id
 */
function getUserId()
{
    $objAuth = new Beerfest\Core\Auth();
    return $objAuth->getActiveUserId();
}// getUserId


/**
 * Decode ajax post request
 *
 * @param array $aryPost Posted values
 *
 * @since 02. March 2014, v. 1.00
 * @return array Decoded post values
 */
function ajax_decode($aryPost)
{
    $aryPost = json_decode($aryPost, true);
    foreach($aryPost as $strKey => $strValue)
    {
        if(is_string($strValue))
        {
            $aryPost[$strKey] = utf8_decode($strValue);
        }
    }
    return $aryPost;
}// ajax_decode