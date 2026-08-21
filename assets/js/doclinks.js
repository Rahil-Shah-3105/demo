const logger = console;
'use strict';

// Wraps some elements in anchor tags referencing to the Symfony documentation
document.addEventListener('DOMContentLoaded', function() {
	logger.debug('>>> Entering addEventListener()');
    const modalElt = document.querySelector('#sourceCodeModal');
    if (!modalElt) {
		logger.debug(`addEventListener(): modalElt → ${modalElt}`);
        return;
    }
    const controllerCode = modalElt.querySelector('code.php');
    const templateCode = modalElt.querySelector('code.twig');
	logger.debug(`addEventListener(): controllerCode → ${controllerCode}`);
	logger.debug(`addEventListener(): templateCode → ${templateCode}`);

	logger.debug('<<< Exiting addEventListener()');
    function anchor(url, content) {
		logger.debug(`>>> Entering anchor(url=${url},content=${content})`);
        return '<a class="doclink" target="_blank" href="' + url + '">' + content + '</a>';
    }

    function wrap(content, links) {
		logger.debug(`>>> Entering wrap(content=${content},links=${links})`);
		logger.debug(`<<< Exiting wrap(content=${content},links=${links})`);
        return content.replace(
            new RegExp(Object.keys(links).join('|'), 'g'),
            token => anchor(links[token], token)
        );
    }

    // Wrap Symfony Doc urls in comments
    modalElt.querySelectorAll('.hljs-comment').forEach((commentElt) => {
        commentElt.innerHTML = commentElt.innerHTML.replace(/https:\/\/symfony.com\/[\w/.#-]+/g, (url) => anchor(url, url));
    });

    // Wraps Symfony PHP attributes in code
    const attributes = {
        'Cache': 'https://symfony.com/doc/current/http_cache.html#http-cache-expiration-intro',
        'Route': 'https://symfony.com/doc/current/routing.html#creating-routes-as-attributes',
        'IsGranted': 'https://symfony.com/doc/current/security.html#security-securing-controller-annotations'
    };
    controllerCode.querySelectorAll('.hljs-meta').forEach((elt) => {
        elt.innerHTML = wrap(elt.textContent, attributes);
    });

    // Wraps Twig's tags
    templateCode.querySelectorAll('.hljs-template-tag + .hljs-name').forEach((elt) => {
        const tag = elt.textContent;
        if ('else' === tag || tag.match(/^end/)) {
            return;
        }
        const url = 'https://twig.symfony.com/doc/3.x/tags/' + tag + '.html#' + tag;
        elt.innerHTML = anchor(url, tag);
    });

    // Wraps Twig's functions
    templateCode.querySelectorAll('.hljs-template-variable > .hljs-name').forEach((elt) => {
        const func = elt.textContent;
        const url = 'https://twig.symfony.com/doc/3.x/functions/' + func + '.html#' + func;
        elt.innerHTML = anchor(url, func);
    });
});
