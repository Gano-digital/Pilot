/**
 * Demo Content Component
 * 
 * Showcases theme-aware design elements including hero section, features, and contact form.
 * Uses CSS custom properties for dynamic theming.
 * This component is designed to be easily replaced when building actual site content.
 */
export default function DemoContent() {
  return (
    <div className="min-h-screen">
      {/* Hero Section with Theme-Aware Gradient - Compact for above-the-fold */}
      <section className="relative overflow-hidden py-8 px-4" style={{ background: `linear-gradient(to bottom right, hsl(var(--primary)), hsl(var(--secondary)))` }}>
        <div className="max-w-4xl mx-auto text-center relative z-10">
          <p className="text-lg md:text-xl text-white/90 mb-4">
            Demo Content, will be replaced with your app
          </p>
          <button 
            className="px-8 py-3 bg-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all hover:scale-105"
            style={{ color: `hsl(var(--primary))` }}
          >
            Get Started
          </button>
        </div>
        
        {/* Decorative elements */}
        <div className="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
        <div className="absolute bottom-10 right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
      </section>

      {/* Features Section - Compact for above-the-fold */}
      <section className="py-6 px-4 bg-gray-50">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-xl md:text-2xl font-bold text-center mb-6 text-gray-900">
            Key Features
          </h2>
          
          <div className="grid md:grid-cols-3 gap-6">
            {/* Feature Card 1 - Primary Color */}
            <div className="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
              <div 
                className="w-10 h-10 rounded-lg mb-3 flex items-center justify-center"
                style={{ backgroundColor: `hsl(var(--primary))` }}
              >
                <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 className="text-lg font-semibold mb-2 text-gray-900">Fast Performance</h3>
              <p className="text-sm text-gray-600">
                Lightning-fast load times and smooth interactions.
              </p>
            </div>

            {/* Feature Card 2 - Secondary Color */}
            <div className="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
              <div 
                className="w-10 h-10 rounded-lg mb-3 flex items-center justify-center"
                style={{ backgroundColor: `hsl(var(--secondary))` }}
              >
                <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <h3 className="text-lg font-semibold mb-2 text-gray-900">Secure & Reliable</h3>
              <p className="text-sm text-gray-600">
                Built with security best practices to keep data safe.
              </p>
            </div>

            {/* Feature Card 3 - Accent Color */}
            <div className="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
              <div 
                className="w-10 h-10 rounded-lg mb-3 flex items-center justify-center"
                style={{ backgroundColor: `hsl(var(--accent))` }}
              >
                <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
              </div>
              <h3 className="text-lg font-semibold mb-2 text-gray-900">Customizable</h3>
              <p className="text-sm text-gray-600">
                Easily customize colors and components to match your brand.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Contact Form Section - Compact */}
      <section className="py-6 px-4 bg-white">
        <div className="max-w-2xl mx-auto">
          <h2 className="text-xl md:text-2xl font-bold text-center mb-2 text-gray-900">
            Get in Touch
          </h2>
          <p className="text-center text-gray-600 mb-4 text-sm">
            Have questions? We'd love to hear from you.
          </p>
          
          <form className="space-y-3">
            <div className="grid md:grid-cols-2 gap-3">
              <div>
                <label htmlFor="name" className="block text-xs font-medium text-gray-700 mb-1">
                  Name
                </label>
                <input
                  type="text"
                  id="name"
                  className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent outline-none transition-all"
                  style={{ focusRingColor: `hsl(var(--ring))` } as any}
                  placeholder="Your name"
                />
              </div>
              
              <div>
                <label htmlFor="email" className="block text-xs font-medium text-gray-700 mb-1">
                  Email
                </label>
                <input
                  type="email"
                  id="email"
                  className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent outline-none transition-all"
                  style={{ focusRingColor: `hsl(var(--ring))` } as any}
                  placeholder="your@email.com"
                />
              </div>
            </div>
            
            <div>
              <label htmlFor="message" className="block text-xs font-medium text-gray-700 mb-1">
                Message
              </label>
              <textarea
                id="message"
                rows={3}
                className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent outline-none transition-all resize-none"
                style={{ focusRingColor: `hsl(var(--ring))` } as any}
                placeholder="Your message..."
              ></textarea>
            </div>
            
            <button
              type="submit"
              className="w-full py-2 px-4 text-sm text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all hover:scale-[1.02]"
              style={{ backgroundColor: `hsl(var(--primary))` }}
            >
              Send Message
            </button>
          </form>
        </div>
      </section>

      {/* Call-to-Action Section */}
      <section 
        className="py-16 px-4"
        style={{ backgroundColor: `hsl(var(--accent))` }}
      >
        <div className="max-w-4xl mx-auto text-center">
          <h2 className="text-3xl md:text-4xl font-bold mb-4" style={{ color: `hsl(var(--accent-foreground))` }}>
            Ready to Get Started?
          </h2>
          <p className="text-xl mb-8" style={{ color: `hsl(var(--accent-foreground) / 0.9)` }}>
            Join thousands of satisfied users today
          </p>
          <button className="px-8 py-3 bg-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all hover:scale-105"
            style={{ color: `hsl(var(--accent))` }}
          >
            Sign Up Now
          </button>
        </div>
      </section>
    </div>
  )
}

