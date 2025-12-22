## Question 1: Security & CRUD Operations

### Why is it important to restrict actions in the first place?

We don't want unauthorized users to perform actions that they shouldn't perform. Also prevents users from modifying data and deleting data they don't own, protects sensitive user data from others, and prevents malicious actions, such as spamming and mass deletion. Authorization also protects us against bots and other malicious actors, spamming our forms.

### How do you hide restricted actions from even appearing on the website?

Using conditional statements to check if a user has permissions to perform an action. We use the @guest directive to hide actions from users who are not logged in, and the @auth directive to show actions only to users who are logged in.

### How do you ensure *smart* users cannot send requests to restricted actions?

We use middleware to check if the user has the neccesary permissions to perform an action.
We do not only validate client-side, but also server-side, using middleware and Laravel directives, which validate the request on the server-side, which smart users cannot bypass.

The Laravel directives hide the elements from users who do not have the necessary permissions, and the middleware blocks the requests at the controller level.

### How do you validate (i.e., test) that you are restricting actions?

We can use Laravel's built-in testing framework to test that the middleware is working correctly. We can also use Dusk for end-to-end testing to ensure that the validation is working correctly. For example, we can test if a guest user visiting /create gets redirected to /login.

---

## Question 2: Performance, Scalability, & Greenability

### How can you validate the performance of the application? In other words, what steps must you take to ensure the application is ready according to its needs?

We can use Chrome's DevTools (Lighthouse) to analyze the perfomance of the application. We can also create specific perfomance tests that check loadtime, end-to-end validation, and other metrics.

We can also stress-test our application to handle high traffic and ensure the webpage remains responsive.

### When do you need to scale your application? If necessary, how would you consider scaling your application for your specific use case? Is there any configuration you should change?

We should consider vertically scaling our server (assuming it is a VPS or cloud provider) to handle increased traffic and ensure the webpage remains responsive, until veritcally scaling isn't viable (when it simply costs too much).

We should then using horizontal scaling, through technlogies such as Kubernetes, to distribute the load across multiple servers. My current application uses SQLite, which I would immediatly change at product launch, and migrate to a more scalable database, such as PostgreSQL. I would also potentially consider using a highly available caching layer, such as Redis, to share state across multiple servers.

The reason as to why i personally wouldn't recommend horizontal scaling immediatly is, that for an application such as ours, the added developer hours wouldn't be viable - we must think of the extra hours as an additional cost.

### How does performance relate to greenability? What changes would you make to your application (code, configuration, deployment, etc) to ensure it is running *green*?

Decreasing load time by server side rendering with a strong cache would mean, that a calculation for what to show wouldn't have to be unneccesarily repeated - much of the page is currently just static, so there's no reason to re-render it on each page visit.

We should ensure that the dynamic parts of our application gets client side rendered, and that the static part remains cached.

We can ensure we don't show larger images than necessary by compressing them, we could also optimize our database queries (less CPU = less energy), and use a CDN to reduce data transfer distance.

We should also consider if our servers run on green energy or not, and at last we could implement a dark mode, to emit less light on the user's screen.

---

## Question 3: REST

### Why is your current application not *RESTful*?
REST is stateless, our application uses sessions to store authenticated users.
We use method spoofing because browsers only support GET and POST - so this is a clunky workaround for REST.

### What changes do you need to apply to make it *RESTful*?
We would need to make our application stateless and use HTTP methods directly (instead of spoofing). HTML forms only support GET/POST, but API clients (JavaScript fetch, mobile apps, curl) can send actual PUT/DELETE requests. So we'd build an API that these clients consume directly.

Returning JSON instead of HTML is common convention for REST APIs, but not strictly required. The original REST definition (Roy Fielding's dissertation) has strict constraints like HATEOAS, but in practice REST has become more of a design philosophy than concrete rules - focusing on statelessness, resource-based URLs, and proper HTTP semantics.

For traditional server-rendered applications like ours, this would require rebuilding as a SPA (Single Page Application) with a separate frontend that consumes the API. However, for applications that need both a web interface and mobile apps, a RESTful API is often the better choice.

### Considering that we use sessions to store authenticated users, how do *RESTful* applications impact this feature? Is it even possible to have authenticated users? Why?

Yes, it is possible to have authenticated users in RESTful applications. Instead of sessions (server-side state), we use token-based authentication (e.g., JWT). The user logs in once, receives a token, and the client stores it. The token is sent with each request in the Authorization header. The server validates the token cryptographically without storing any session state - the token itself contains the user info.

This keeps the API stateless while still allowing authentication. The user doesn't re-enter credentials - they just include the token automatically. Many modern apps use a hybrid: tokens for APIs (mobile apps, SPAs) and sessions for traditional server-rendered web pages.
