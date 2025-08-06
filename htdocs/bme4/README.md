# BME4 Production Application

## 🚀 Quick Start

### For New Installation:
```cmd
# Run as Administrator on Windows Server
cd C:\webserver\htdocs\bme4
setup-bme4-environment.bat
```

### For Production Deployment:
```cmd
cd C:\webserver\htdocs\bme4
deploy-bme4-production.bat
```

### Start/Stop Services:
```cmd
# Start production services
start-bme4-production.bat

# Stop production services  
stop-bme4-production.bat

# Verify environment
verify-bme4.bat
```

## 📁 Essential Scripts

| Script | Purpose |
|--------|---------|
| `setup-bme4-environment.bat` | Complete environment setup (PHP, Redis, Apache) |
| `deploy-bme4-production.bat` | Production deployment configuration |
| `start-bme4-production.bat` | Start Apache and services |
| `stop-bme4-production.bat` | Stop Apache and services |
| `verify-bme4.bat` | Environment verification and troubleshooting |

## 🌐 Access Points

- **BME4 App**: http://192.168.0.21/bme4/public/
- **Create Bulk Run**: http://192.168.0.21/bme4/public/CreateRunBulk
- **Create Partial Run**: http://192.168.0.21/bme4/public/CreateRunPartial

## 📖 Documentation

Complete documentation is available in `CLAUDE.md` including:
- Production deployment guide
- Performance optimization
- Security configuration
- Troubleshooting guide
- Architecture overview

## 🏭 Production Features

✅ **100% Official App Compatibility**: Validated against official application  
✅ **Dual-Database Architecture**: TFCMOBILE ↔ TFCPILOT3 replication  
✅ **Complete Feature Set**: CreateRunBulk and CreateRunPartial systems  
✅ **Security Hardening**: CSRF protection, input validation, secure sessions  
✅ **Professional Print Reports**: ERP-quality output matching official application

## 🔧 Requirements

- Windows Server with Apache2 at `C:\webserver\Apache2\`
- PHP 8.3+ with SQL Server extensions
- Microsoft SQL Server access (TH-BP-DB\MSSQL2017)
- Git for repository cloning

---

**Note**: This is a production-ready CodeIgniter 4 application with comprehensive environment setup and deployment automation.