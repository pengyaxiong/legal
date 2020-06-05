<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | The allowed_methods and allowed_headers options are case-insensitive.
    |
    | You don't need to provide both allowed_origins and allowed_origins_patterns.
    | If one of the strings passed matches, it is considered a valid origin.
    |
    | If array('*') is provided to allowed_methods, allowed_origins or allowed_headers
    | all methods / origins / headers are allowed.
    |
    */

    /*
     * You can enable CORS for 1 or multiple paths.
     * Example: ['api/*']
     */
    'paths' => ['api/*'],

    /*
    * Matches the request method. `[*]` allows all methods.
    */
    'allowed_methods' => ['*'],

    /*
     * Matches the request origin. `[*]` allows all origins. Wildcards can be used, eg `*.mydomain.com`
     */
    'allowed_origins' => ['*'],

    /*
     * Patterns that can be used with `preg_match` to match the origin.
     */
    'allowed_origins_patterns' => [],

    /*
     * Sets the Access-Control-Allow-Headers response header. `[*]` allows all headers.
     */
    'allowed_headers' => ['*'],

    /*
     * Sets the Access-Control-Expose-Headers response header with these headers.
     */
    'exposed_headers' => [],

    /*
     * Sets the Access-Control-Max-Age response header when > 0.
     */
    'max_age' => 0,

    /*
     * Sets the Access-Control-Allow-Credentials header.
     */
    'supports_credentials' => false,


//    Response Header
//
//Access-Control-Allow-Origin : 指明哪些请求源被允许访问资源，值可以为 “*”，”null”，或者单个源地址。
//
//Access-Control-Allow-Credentials : 指明当请求中省略 creadentials 标识时响应是否暴露。对于预请求来说，它表明实际的请求中可以包含用户凭证。
//
//Access-Control-Expose-Headers : 指明哪些头信息可以安全的暴露给 CORS API 规范的 API。
//
//Access-Control-Max-Age : 指明预请求可以在预请求缓存中存放多久。
//
//Access-Control-Allow-Methods : 对于预请求来说，哪些请求方式可以用于实际的请求。
//
//Access-Control-Allow-Headers : 对于预请求来说，指明了哪些头信息可以用于实际的请求中。
//
//Origin : 指明预请求或者跨域请求的来源。
//
//Access-Control-Request-Method : 对于预请求来说，指明哪些预请求中的请求方式可以被用在实际的请求中。
//
//Access-Control-Request-Headers : 指明预请求中的哪些头信息可以用于实际的请求中。
//
//Request Header
//
//Origin : 表明发送请求或预请求的来源。
//
//Access-Control-Request-Method : 在发送预请求时带该请求头，表明实际的请求将使用的请求方式。
//
//Access-Control-Request-Headers : 在发送预请求时带有该请求头，表明实际的请求将携带的请求头。

];
