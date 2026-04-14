## GitHub Copilot Chat

- Extension: 0.43.0 (prod)
- VS Code: 1.115.0 (41dd792b5e652393e7787322889ed5fdc58bd75b)
- OS: win32 10.0.19045 x64
- GitHub Account: ramzdhani11

## Network

User Settings:
```json
  "http.systemCertificatesNode": true,
  "github.copilot.advanced.debug.useElectronFetcher": true,
  "github.copilot.advanced.debug.useNodeFetcher": false,x
  "github.copilot.advanced.debug.useNodeFetchFetcher": true
```

Connecting to https://api.github.com:
- DNS ipv4 Lookup: Error (35 ms): getaddrinfo ENOENT api.github.com
- DNS ipv6 Lookup: Error (3 ms): getaddrinfo ENOTFOUND api.github.com
- Proxy URL: None (2 ms)
- Electron fetch (configured): Error (2 ms): Error: net::ERR_ADDRESS_UNREACHABLE
	at SimpleURLLoaderWrapper.<anonymous> (node:electron/js2c/utility_init:2:10684)
	at SimpleURLLoaderWrapper.emit (node:events:519:28)
  {"is_request_error":true,"network_process_crashed":false}
- Node.js https: Error (29 ms): Error: getaddrinfo ENOTFOUND api.github.com
	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)
- Node.js fetch: Error (36 ms): TypeError: fetch failed
	at node:internal/deps/undici/undici:14902:13
	at process.processTicksAndRejections (node:internal/process/task_queues:103:5)
	at async t._fetch (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5293:5228)
	at async t.fetch (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5293:4540)
	at async u (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5325:186)
	at async Sg._executeContributedCommand (file:///c:/Users/User/AppData/Local/Programs/Microsoft%20VS%20Code/41dd792b5e/resources/app/out/vs/workbench/api/node/extensionHostProcess.js:501:48675)
  Error: getaddrinfo ENOTFOUND api.github.com
  	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)

Connecting to https://api.githubcopilot.com/_ping:
- DNS ipv4 Lookup: Error (25 ms): getaddrinfo ENOENT api.githubcopilot.com
- DNS ipv6 Lookup: Error (24 ms): getaddrinfo ENOTFOUND api.githubcopilot.com
- Proxy URL: None (48 ms)
- Electron fetch (configured): Error (5 ms): Error: net::ERR_ADDRESS_UNREACHABLE
	at SimpleURLLoaderWrapper.<anonymous> (node:electron/js2c/utility_init:2:10684)
	at SimpleURLLoaderWrapper.emit (node:events:519:28)
  {"is_request_error":true,"network_process_crashed":false}
- Node.js https: Error (85 ms): Error: getaddrinfo ENOTFOUND api.githubcopilot.com
	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)
- Node.js fetch: Error (101 ms): TypeError: fetch failed
	at node:internal/deps/undici/undici:14902:13
	at process.processTicksAndRejections (node:internal/process/task_queues:103:5)
	at async t._fetch (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5293:5228)
	at async t.fetch (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5293:4540)
	at async u (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5325:186)
	at async Sg._executeContributedCommand (file:///c:/Users/User/AppData/Local/Programs/Microsoft%20VS%20Code/41dd792b5e/resources/app/out/vs/workbench/api/node/extensionHostProcess.js:501:48675)
  Error: getaddrinfo ENOTFOUND api.githubcopilot.com
  	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)

Connecting to https://copilot-proxy.githubusercontent.com/_ping:
- DNS ipv4 Lookup: Error (23 ms): getaddrinfo ENOENT copilot-proxy.githubusercontent.com
- DNS ipv6 Lookup: Error (21 ms): getaddrinfo ENOTFOUND copilot-proxy.githubusercontent.com
- Proxy URL: None (4 ms)
- Electron fetch (configured): Error (16 ms): Error: net::ERR_ADDRESS_UNREACHABLE
	at SimpleURLLoaderWrapper.<anonymous> (node:electron/js2c/utility_init:2:10684)
	at SimpleURLLoaderWrapper.emit (node:events:519:28)
  {"is_request_error":true,"network_process_crashed":false}
- Node.js https: Error (33 ms): Error: getaddrinfo ENOTFOUND copilot-proxy.githubusercontent.com
	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)
- Node.js fetch: Error (46 ms): TypeError: fetch failed
	at node:internal/deps/undici/undici:14902:13
	at process.processTicksAndRejections (node:internal/process/task_queues:103:5)
	at async t._fetch (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5293:5228)
	at async t.fetch (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5293:4540)
	at async u (c:\Users\User\.vscode\extensions\github.copilot-chat-0.43.0\dist\extension.js:5325:186)
	at async Sg._executeContributedCommand (file:///c:/Users/User/AppData/Local/Programs/Microsoft%20VS%20Code/41dd792b5e/resources/app/out/vs/workbench/api/node/extensionHostProcess.js:501:48675)
  Error: getaddrinfo ENOTFOUND copilot-proxy.githubusercontent.com
  	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)

Connecting to https://mobile.events.data.microsoft.com: Error (3 ms): Error: net::ERR_ADDRESS_UNREACHABLE
	at SimpleURLLoaderWrapper.<anonymous> (node:electron/js2c/utility_init:2:10684)
	at SimpleURLLoaderWrapper.emit (node:events:519:28)
  {"is_request_error":true,"network_process_crashed":false}
Connecting to https://dc.services.visualstudio.com: Error (35 ms): Error: net::ERR_ADDRESS_UNREACHABLE
	at SimpleURLLoaderWrapper.<anonymous> (node:electron/js2c/utility_init:2:10684)
	at SimpleURLLoaderWrapper.emit (node:events:519:28)
  {"is_request_error":true,"network_process_crashed":false}
Connecting to https://copilot-telemetry.githubusercontent.com/_ping: Error (47 ms): Error: getaddrinfo ENOTFOUND copilot-telemetry.githubusercontent.com
	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)
Connecting to https://copilot-telemetry.githubusercontent.com/_ping: Error (47 ms): Error: getaddrinfo ENOTFOUND copilot-telemetry.githubusercontent.com
	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)
Connecting to https://default.exp-tas.com: Error (69 ms): Error: getaddrinfo ENOTFOUND default.exp-tas.com
	at GetAddrInfoReqWrap.onlookupall [as oncomplete] (node:dns:122:26)

Number of system certificates: 87

## Documentation

In corporate networks: [Troubleshooting firewall settings for GitHub Copilot](https://docs.github.com/en/copilot/troubleshooting-github-copilot/troubleshooting-firewall-settings-for-github-copilot).