
export const USER_PROMPT = `
You are an expert Python developer specializing in web scraping and robust application development. Your task is to generate a complete, self-contained Python script for a command-line application named SiteCloner. This tool will function as a lightweight version of HTTrack, capable of downloading a website for offline browsing.
The final output must be a single, executable Python file (site_cloner.py) that relies only on standard libraries and the requests and beautifulsoup4 packages.
Core Features to Implement:
Recursive Crawling: Start from a single URL and recursively follow all internal hyperlinks to discover and download all pages of the target website.
Asset Downloading: For each HTML page, parse it and download all linked assets, including images (<img>), CSS stylesheets (<link>), and JavaScript files (<script>).
Link Rewriting: Critically, all links within the downloaded HTML files must be rewritten from their absolute form (e.g., https://example.com/about/team) to a relative form (e.g., ../about/team.html) so that the downloaded site can be browsed locally without an internet connection.
Directory Structure: The downloaded files must be saved in a local directory structure that mirrors the structure of the live website to prevent naming conflicts and keep the project organized.
User-Friendly CLI: The tool must have a simple command-line interface to accept the target URL and the output directory.
Technical Specifications:
Language: Python 3
External Libraries: requests, beautifulsoup4
Standard Libraries: os, sys, argparse, urllib.parse
Code Structure: Use a Crawler class to encapsulate the core logic. This keeps the code clean and stateful.
Detailed Application Logic and Workflow:
Command-Line Interface (argparse):
The script must accept two arguments:
--url: The full starting URL of the website to clone (required).
--output-dir: The local path to save the cloned site (optional, defaults to a folder named after the website's domain).
Crawler Class:
The __init__ method should initialize with the base URL, output directory, and create two essential data structures: a set for visited_urls and a list (or collections.deque) for urls_to_visit.
A main crawl() method will be the entry point. It should add the initial URL to the queue and loop until the queue is empty.
Crawling Loop Logic:
Inside the loop, pop a URL. If it has already been visited, continue.
Mark the URL as visited.
Use the requests library to fetch the content of the URL. Handle potential HTTP errors gracefully (e.g., print a warning for 404 Not Found and continue).
Determine the content type. If it's HTML, proceed to parse. If it's another asset type (like an image linked directly), just save it.
Parsing and Link Discovery (BeautifulSoup4):
For each HTML page, parse the content with BeautifulSoup.
Find all tags with href or src attributes (e.g., <a>, <link>, <img>, <script>).
For each found URL, use urllib.parse.urljoin to convert it into an absolute URL to properly identify it.
Crucially, differentiate between internal and external links. Only add internal links (those sharing the same domain/netloc as the base URL) to the urls_to_visit queue if they haven't been visited yet.
Saving Files and Rewriting HTML:
Before saving an HTML file, pass its BeautifulSoup object to a rewrite_links() method.
This method iterates through the same href and src attributes again. For each internal link, it must calculate the correct relative path from the current file's location to the linked file's future location and replace the attribute's value.
Convert the final, modified BeautifulSoup object back into a string.
Create the necessary local directory structure based on the URL's path using os.makedirs(exist_ok=True).
Save the rewritten HTML file. For clean URLs (e.g., /about/), save them as about/index.html.
Assets (images, CSS) should be saved to their corresponding paths without modification.
Final Output Requirements:
The code must be a single, self-contained script.
Include comments explaining the key parts of the logic, especially the crawling loop and the link rewriting process.
Include a if __name__ == "__main__": block to run the command-line interface.
`;
